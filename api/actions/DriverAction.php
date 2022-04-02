<?php
namespace api\actions;
use api\models\database\CloseDay;
use api\models\database\Customers;
use api\models\database\DriversBalance;
use api\models\database\DriversBalanceDetail;
use api\models\database\Ingredients;
use api\models\database\Orders;
use api\models\database\OrderStatus;
use api\models\database\Pcategory;
use api\models\database\Poses;
use api\models\database\Products;
use api\models\database\Safe;
use api\models\database\SafeBalance;
use api\models\database\SafeBalanceDetail;
use api\models\database\Status;
use api\models\database\TestOrders;
use api\models\database\Timesheet;
use api\models\database\User;
use api\models\response\CustomerResponse;
use api\models\response\ProductsResponse;
use api\models\response\Result;

use yii\base\Exception;
use yii\helpers\Json;

class DriverAction {

    public static function getSales($day) {
        $day = explode("to",$day);
        $start_day= trim($day[0])." 00:00:01";
        $end_day= trim($day[1])." 23:59:59";

        $sql = "SELECT sum(`amount`) as cash, sum(`card`) as card,sum(`tip`) as tip FROM drivers_balance 
                where created_at >= :start_day and   created_at <= :end_day   ";
        $cmd =\Yii::$app->db->createCommand($sql)
            ->bindValue(":start_day", $start_day)
            ->bindValue(":end_day", $end_day);

        $row =  $cmd->queryOne(\PDO::FETCH_ASSOC);



        $sql_add_balance = "SELECT sum(amount) as amount FROM `drivers_balance_detail` WHERE 
                                     `action` = :payment_method and   created_at >= :start_day and  created_at <= :end_day   ";


        $cmd_add_balance =\Yii::$app->db->createCommand($sql_add_balance);
        $cmd_add_balance->bindValue(":payment_method", "Add balance")
            ->bindValue(":start_day", $start_day)
            ->bindValue(":end_day", $end_day);
        $row_add =  $cmd_add_balance->queryOne(\PDO::FETCH_ASSOC);





        return ["cash"=>number_format((float)($row["cash"]-$row_add["amount"]-$row["tip"]), 2, '.', ''),
            "card"=>number_format((float)($row["card"]+$row["tip"]), 2, '.', ''),
        ];


    }

    public static function getDriver($branch = null) {
        if (!$branch)
            return false;
        $date = date("Y-m-d");
        $sql = "SELECT {{u}}.[[id]], {{u}}.[[username]], {{db}}.[[amount]] FROM {{user}} {{u}}
                    LEFT JOIN {{auth_assignment}} {{aa}} ON {{aa}}.[[user_id]] = {{u}}.[[id]]
                    LEFT JOIN {{drivers_balance}} {{db}} ON {{db}}.[[driver_id]] = {{u}}.[[id]] AND {{db}}.[[created_at]] LIKE '$date%'
                    WHERE {{u}}.[[branch]] = :branch AND {{aa}}.[[item_name]] = 'Courier' ";

        $rows = \Yii::$app->db->createCommand($sql)
            ->bindValue(":branch", $branch)
            ->queryAll(\PDO::FETCH_ASSOC);

        return $rows;
    }

    public static function getClockedInDriverOld($branch = null) {
        $branch = 'digomi';
        $date = date("Y-m-d");
        $time = strtotime(date('Y-m-d')." 00:00:00");

        $sql = "SELECT {{u}}.[[id]], {{u}}.[[username]], {{u}}.[[fullname]], {{db}}.[[amount]],{{db}}.[[card]], {{cd}}.[[driver_id]] as [[closed]] FROM {{user}} {{u}}
                    LEFT JOIN {{auth_assignment}} {{aa}} ON {{aa}}.[[user_id]] = {{u}}.[[id]]
                    LEFT join {{timesheet}} {{t}} ON {{u}}.[[id]] = {{t}}.[[user_id]]
                    LEFT join {{close_day}} {{cd}} ON {{u}}.[[id]] = {{cd}}.{{driver_id}}  and {{cd}}.[[created_at]] like '$date%'  
                    LEFT JOIN {{drivers_balance}} {{db}} ON {{db}}.[[driver_id]] = {{u}}.[[id]] AND {{db}}.[[created_at]] LIKE '$date%'
                    WHERE  {{cd}}.[[driver_id]] is null AND {{t}}.[[state]] = :state and  {{t}}.[[start_date]] >= :start_date  and {{u}}.[[branch]] = :branch AND {{aa}}.[[item_name]] = 'Courier' 
                    group by {{u}}.[[id]]";

        $rows = \Yii::$app->db->createCommand($sql)
            ->bindValue(":state", "IN")
            ->bindValue(":start_date", $time)
            ->bindValue(":branch", $branch)
            ->queryAll(\PDO::FETCH_ASSOC);

         $result = [];
         foreach ($rows as $r) {
             $in_way = Orders::find()->where(["driver_id"=>$r["id"]])->andWhere(["status"=>OrderStatus::getInDelivery()])->all();
             $r2 = array_merge( $r, ["in_way"=>$in_way?true:false]);

             $result[] = $r2;
         }
         return  $result;
    }

    public static function getClockedInDriver($branch = null) {
        $branch = 'digomi';
        $date = date("Y-m-d");
        $time = "";

        $sql = "SELECT {{t}}.*, {{u}}.[[id]], {{u}}.[[username]], {{u}}.[[fullname]],{{db}}.[[amount]],{{db}}.[[tip]],{{db}}.[[card]]  FROM {{timesheet}} {{t}}
                    LEFT JOIN {{auth_assignment}} {{aa}} ON {{aa}}.[[user_id]] = {{t}}.[[user_id]]
                    LEFT JOIN {{user}} {{u}} ON {{u}}.[[id]] = {{t}}.[[user_id]]
                    LEFT JOIN {{drivers_balance}} {{db}} ON {{db}}.[[driver_id]] = {{t}}.[[user_id]] AND {{db}}.[[end_time]] is null 
                    WHERE  {{t}}.[[state]] = :state and  {{t}}.[[end_date]] = :end_date  AND {{aa}}.[[item_name]] = 'Courier' 
                    group by {{t}}.[[user_id]]";

        $rows = \Yii::$app->db->createCommand($sql)
            ->bindValue(":state", "IN")
            ->bindValue(":end_date", $time)
            ->queryAll(\PDO::FETCH_ASSOC);

        $result = [];
        $out_times = [];
        foreach ($rows as $r) {
            $in_way = Orders::find()->where(["driver_id"=>$r["id"]])->andWhere(["status"=>OrderStatus::getInDelivery()])->one();

            $driver_order = Orders::find()->where(["driver_id"=>$r["id"]])->andWhere([">","created_at",date("Y-m-d H:i:s", $r["start_date"])])->count();

            $r["amount"] =  $r["amount"] - $r["tip"];
            $r["card"] =  $r["card"] + $r["tip"];
            $r["count"] = $driver_order;
            $r2 = array_merge( $r, ["in_way"=>$in_way?true:false, "start_delivery"=>$in_way?$in_way["start_delivery"]:0]);
            $result[] = $r2;
        }
        return  $result;
    }
    public static function getUncloseDrivers($branch = null) {

        $driver_balances = DriversBalance::find()->where(["end_time"=>null])->all();

        $result = [];

        foreach ($driver_balances as $r) {
            $timesheet = Timesheet::find()->where(["user_id"=>$r["driver_id"]])->orderBy(["created_at"=>SORT_DESC])->limit(1)->one();
            if($timesheet["state"] == "FINISH") {
                $user = User::find()->where(["id"=>$r["driver_id"]])->asArray()->one();
                $result[] = array_merge($user,["tip"=>$r->tip,"amount"=>($r->amount-$r->tip),"card"=>($r->card+$r->tip)]);

            }

        }
        return  $result;
    }

    public static function getUncloseDriversold($branch = null) {



        $branch = 'digomi';
        $date = date("Y-m-d");
        $time = "";

        /*$sql = "SELECT {{t}}.*,max({{t}}.[[created_at]]) as [[created_at]], {{u}}.[[username]], {{u}}.[[fullname]] FROM {{timesheet}} {{t}}
                    LEFT JOIN {{auth_assignment}} {{aa}} ON {{aa}}.[[user_id]] = {{t}}.[[user_id]]
                    LEFT JOIN {{user}} {{u}} ON {{u}}.[[id]] = {{t}}.[[user_id]]
                    WHERE   {{aa}}.[[item_name]] = 'Courier'
                    order by {{t}}.[[id]] desc ";*/

        $sql = "SELECT t.*, t.user_id as id, u.username, u.fullname 
FROM timesheet t 
 LEFT JOIN auth_assignment aa ON aa.user_id = t.user_id
 LEFT JOIN user u ON u.id = t.user_id

WHERE t.id IN (
    SELECT MAX(id)
    FROM timesheet
    GROUP BY user_id
) and   aa.item_name = 'Courier'";

        $rows = \Yii::$app->db->createCommand($sql)

            ->queryAll(\PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $r) {
            if ($r["state"]=="FINISH") {
                $timesheet = Timesheet::find()->where(["state"=>"IN"])->andWhere(["user_id"=>$r["id"]])->orderBy(["created_at"=>SORT_DESC])->limit(1)->one();

                $is_close = CloseDay::find()->where(["driver_id"=>$r["user_id"]])->andWhere([">=","created_at",$timesheet["created_at"]])->all();


                if(!$is_close) {
                $driver_balance = DriversBalance::find()->where(["driver_id"=>$r["id"]])->orderBy(['id' => SORT_DESC])->andWhere(["end_time"=>null])->one();
                if($driver_balance)
                $result[] = array_merge($r,["tip"=>$driver_balance->tip,"amount"=>($driver_balance->amount-$driver_balance->tip),"card"=>($driver_balance->card+$driver_balance->tip)]);


                }

            }
        }
        return  $result;
    }

    public static function getDriverBalance($driver_id= null, $day = null) {
        $drivers_balance = DriversBalance::find()->where(["driver_id"=>$driver_id])->andWhere(["like","created_at", $day."%", false])->one();
        if ($drivers_balance)
            return $drivers_balance->amount;
        else return 0;


    }

    public static function AddbalanceToDriver($driver_id= null, $amount = null) {
        $data = date("Y-m-d");
        $drivers_balance = DriversBalance::find()->where(["driver_id"=>$driver_id])->andWhere(["like","created_at", $data."%", false])->one();
        if ($drivers_balance){
            $drivers_balance->amount =  ($drivers_balance->amount + $amount);
        } else {
            $drivers_balance = new DriversBalance();
            $drivers_balance->amount = $amount;
            $drivers_balance->driver_id = $driver_id;
        }
        $driver_balance_detail = new DriversBalanceDetail();
        $driver_balance_detail->user_id = \Yii::$app->user->getId();
        $driver_balance_detail->driver_id = $driver_id;
        $driver_balance_detail->amount = $amount;
        $driver_balance_detail->action = "By manager";
        $driver_balance_detail->save();

        if ($drivers_balance->save())
            return $drivers_balance->amount;
        else return -1;


    }

    public static function EditDriverBalance($driver_id = null, $amount = null) {

        $date = date("Y-m-d");

        $driver_balance = DriversBalance::find()->where(["driver_id"=>$driver_id])->andWhere(["end_time"=>null])->one();

        self::editSafeBalance($driver_id, $amount);

        if ($driver_balance){
            $driver_balance->amount =  ($driver_balance->amount + $amount);
        } else {
            $driver_balance = new DriversBalance();
            $driver_balance->amount = $amount;
            $driver_balance->start_time = date('Y-m-d H:i:s');
            $driver_balance->driver_id = $driver_id;
        }
        $driver_balance_detail = new DriversBalanceDetail();
        $driver_balance_detail->user_id = \Yii::$app->user->getId();
        $driver_balance_detail->driver_id = $driver_id;
        $driver_balance_detail->amount = $amount;
        $driver_balance_detail->action = "Add balance";
        $driver_balance_detail->save();

        if ($driver_balance->save())
            return $driver_balance->amount;
        else return -1;




    }

    public static function editSafeBalance($driver_id = null , $amount = null) {

        $driver = User::find()->where(["id"=>$driver_id])->andWhere(["status"=>10])->one();
        $safe = Safe::find()->where(["branch"=>$driver["branch"]])->andWhere(["status"=>Status::getActive()])->one();

            $safe->amount = $safe->amount-$amount;
            $safe->save();

        $date = date("Y-m-d");
        $safe_balance = SafeBalance::find()->where(["safe_id"=>$safe->id])->andWhere(["like","created_at", $date."%", false])->one();

        if ($safe_balance){
            $safe_balance->amount =  ($safe_balance->amount - $amount);
        } else {
            $safe_balance = new SafeBalance();
            $safe_balance->amount = -$amount;
            $safe_balance->safe_id = $safe->id;
        }

        $safe_balance_detail = new SafeBalanceDetail();
        $safe_balance_detail->user_id = \Yii::$app->user->getId();
        $safe_balance_detail->driver_id = $driver_id;
        $safe_balance_detail->amount = -$amount;
        $safe_balance_detail->safe_id = $safe->id;
        $safe_balance_detail->payment = "edit balance from driver";
        $safe_balance_detail->save();

        if ($safe_balance->save())
            return $safe_balance->amount;
        else return -1;
    }
}