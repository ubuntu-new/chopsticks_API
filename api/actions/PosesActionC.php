<?php
namespace api\actions;
use api\models\database\Branches;
use api\models\database\Customers;
use api\models\database\DriversBalance;
use api\models\database\DriversBalanceDetail;
use api\models\database\Ingredients;
use api\models\database\Orders;
use api\models\database\Pcategory;
use api\models\database\Poses;
use api\models\database\PosesBalance;
use api\models\database\PosesBalanceDetail;
use api\models\database\PosesToCashier;
use api\models\database\Products;
use api\models\database\Safe;
use api\models\database\SafeBalance;
use api\models\database\SafeBalanceDetail;
use api\models\database\Status;
use api\models\database\TestOrders;
use api\models\database\User;
use api\models\response\CustomerResponse;
use api\models\response\ProductsResponse;
use api\models\response\Result;

use api\models\response\SafeResponse;
use yii\base\Exception;
use yii\helpers\Json;

class PosesActionC {

    public static function getBranchList() {
        return Branches::find()->where(["status"=>Status::getActive()])->all();
    }

    public static function getPosesList() {
        $m = explode(",",\Yii::$app->user->identity->branch);

        return Poses::find()->where(["IN", "branch_name" , $m])->all();
    }


    public static function getPosesListByBranch($branch_id = null) {

        return Poses::find()->where(["branch_id"=>$branch_id])->all();
    }

    public static function getPosesByMac($mac = null) {
        return Poses::find()->where(["mac"=>$mac])->all();
    }

    public static function AddbalanceToPos($pos_id = null, $amount = null) {
        $data = date("Y-m-d");
        $poses_balance = PosesBalance::find()->where(["poses_id"=>$pos_id])->andWhere(["like","created_at", $data."%", false])->one();
        if($amount > 0) {
            $poses = Poses::find()->where(["id"=>$pos_id])->andWhere(["status"=>Status::getActive()])->one();
            $safe = Safe::find()->where(["branch_id"=>$poses["branch_id"]])->andWhere(["status"=>Status::getActive()])->one();
            self::dropSafeBalance($safe["id"], $amount);
        }
        if ($poses_balance){
            $poses_balance->amount =  ($poses_balance->amount + $amount);
        } else {
            $poses_balance = new PosesBalance();
            $poses_balance->amount = $amount;
            $poses_balance->poses_id = $pos_id;
        }
        $poses_balance_detail = new PosesBalanceDetail();
        $poses_balance_detail->user_id = \Yii::$app->user->getId();
        $poses_balance_detail->pos_id = $pos_id;
        $poses_balance_detail->amount = $amount;
        $poses_balance_detail->payment = "Add balance";
        $poses_balance_detail->save();

        if ($poses_balance->save())
            return $poses_balance->amount;
        else return -1;


    }

    public static function AddbalanceToSafe($safe_id = null, $pos_id = null, $amount = null) {
        $data = date("Y-m-d");
        $safe_balance = SafeBalance::find()->where(["safe_id"=>$safe_id])->andWhere(["like","created_at", $data."%", false])->one();

        if ($safe_balance){
            $safe_balance->amount =  ($safe_balance->amount + $amount);
        } else {
            $safe_balance = new SafeBalance();
            $safe_balance->amount = $amount;
            $safe_balance->safe_id = $safe_id;
        }
        $safe_balance_detail = new SafeBalanceDetail();
        $safe_balance_detail->user_id = \Yii::$app->user->getId();
        $safe_balance_detail->pos_id = $pos_id;
        $safe_balance_detail->amount = $amount;
        $safe_balance_detail->safe_id = $safe_id;
        $safe_balance_detail->payment = "Add balance";
        $safe_balance_detail->save();

        if ($safe_balance->save())
            return $safe_balance->amount;
        else return -1;


    }

    public static function dropSafeBalance($safe_id, $amount) {
        $data = date("Y-m-d");
        $safe_balance = SafeBalance::find()->where(["safe_id"=>$safe_id])->andWhere(["like","created_at", $data."%", false])->one();

        if ($safe_balance){
            $safe_balance->amount =  ($safe_balance->amount + $amount);
        }

        $safe_balance_detail = new SafeBalanceDetail();
        $safe_balance_detail->user_id = \Yii::$app->user->getId();
        $safe_balance_detail->pos_id = 0;
        $safe_balance_detail->amount = $amount;
        $safe_balance_detail->safe_id = $safe_id;
        $safe_balance_detail->payment = "edit balance";
        $safe_balance_detail->save();

        if ($safe_balance->save())
            return $safe_balance->amount;
        else return -1;

    }

    public static function getSafe($branch_id, $day) {
        $sql = "SELECT * FROM safe s where s.branch_id = :branch_id ";

        $cmd =\Yii::$app->db->createCommand($sql);
        $cmd->bindValue(":branch_id", $branch_id);
        $row = $cmd->queryAll(\PDO::FETCH_ASSOC);
   $result = [];
    foreach ($row as $c)
    {
        $result[] = new  SafeResponse($c, $day);
    }
    return $result;
    }

    public static function getPoses($branch_id, $day) {
        $sql = "SELECT * FROM poses p 
                left join poses_balance pb ON pb.poses_id = p.id
                where p.branch_id = :branch_id and pb.created_at LIKE :dayt";

        $cmd =\Yii::$app->db->createCommand($sql);
        $cmd->bindValue(":branch_id", $branch_id)
            ->bindValue(":dayt", $day."%");
        return $cmd->queryAll(\PDO::FETCH_ASSOC);
    }

    public static function getDriver($branch = null) {
        if (!$branch)
            return false;
        $date = date("Y-m-d");
        $sql = "SELECT {{u}}.[[id]], {{u}}.[[username]], {{db}}.[[amount]] FROM {{user}} {{u}}
                    LEFT JOIN {{auth_assignment}} {{aa}} ON {{aa}}.[[user_id]] = {{u}}.[[id]]
                    LEFT JOIN {{drivers_balance}} {{db}} ON {{db}}.[[driver_id]] = {{u}}.[[id]] AND {{db}}.[[created_at]] LIKE '$date%'
                    WHERE {{u}}.[[branch]] = :branch AND {{aa}}.[[item_name]] = 'driver'";

        $rows = \Yii::$app->db->createCommand($sql)
            ->bindValue(":branch", $branch)
            ->queryAll(\PDO::FETCH_ASSOC);

        return $rows;
    }

    public static function getDriverBalance($driver_id= null, $day = null) {

        $drivers_balance = DriversBalance::find()->where(["driver_id"=>$driver_id])->andWhere(["like","created_at", $day."%", false])->one();
        if ($drivers_balance)
            return $drivers_balance->amount;
        else return 0;


    }

    public static function GetMyBalance() {
        $user_id = \Yii::$app->user->getId();
        $day = date("Y-m-d");
        $pos = PosesToCashier::find()->where(["user_id"=>$user_id])->andWhere(["like","created_at", $day."%", false])
            ->orderBy(["id"=>SORT_DESC])->one();

        return PosesBalance::find()->where(["poses_id"=>$pos->id])->andWhere(["like","created_at", $day."%", false])->one();



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
        $driver_balance_detail->action = "by manager";
        $driver_balance_detail->save();

        if ($drivers_balance->save())
            return $drivers_balance->amount;
        else return -1;


    }







}