<?php
namespace api\actions;
use api\models\database\Banks;
use api\models\database\Branches;
use api\models\database\CloseDay;
use api\models\database\Customers;
use api\models\database\DeliveryMethods;
use api\models\database\DriversBalance;
use api\models\database\DriversBalanceDetail;
use api\models\database\Ingredients;
use api\models\database\Orders;
use api\models\database\OrderStatus;
use api\models\database\PaymentMethods;
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
use api\models\database\Timesheet;
use api\models\database\User;
use api\models\response\CustomerResponse;
use api\models\response\ProductsResponse;
use api\models\response\Result;

use api\models\response\SafeResponse;
use phpDocumentor\Reflection\Types\Self_;
use yii\base\Exception;
use yii\db\mssql\PDO;
use yii\helpers\Json;

class PosesAction {

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

    public static function getPoses($branch_id, $day) {
        $sql = "SELECT * FROM poses p 
                inner join poses_balance pb ON pb.poses_id = p.id
                where p.branch_id = :branch_id and (pb.created_at LIKE :dayt) AND pb.end_time is null ";

        $sql_unclose = "SELECT * FROM poses p 
                inner join poses_balance pb ON pb.poses_id = p.id
                where p.branch_id = :branch_id and pb.created_at <= :dayt and pb.end_time is null ";

        $cmd =\Yii::$app->db->createCommand($sql);
        $cmd->bindValue(":branch_id", $branch_id)
            ->bindValue(":dayt", $day."%");
        $row =  $cmd->queryAll(\PDO::FETCH_ASSOC);

        $cmd_unclose =\Yii::$app->db->createCommand($sql_unclose);
        $cmd_unclose->bindValue(":branch_id", $branch_id)
            ->bindValue(":dayt", $day."%");
        $row_unclose =  $cmd_unclose->queryAll(\PDO::FETCH_ASSOC);

        return ["current"=>$row, "unclose"=>$row_unclose];


    }

    public static function getSalesByPos($branch_id, $day) {
        $day = explode("to",$day);
        $start_day= trim($day[0])." 00:00:01";
        $end_day= trim($day[1])." 23:59:59";

        $sql = "SELECT sum(`cash`) as cash, sum(`card`) as card, sum(`glovo`) as glovo, sum(`glovo_cash`) as glovo_cash, sum(`glovo_card`) as glovo_card, sum(`wolt_card`) as wolt_card FROM poses p 
                inner join poses_balance pb ON pb.poses_id = p.id
                where p.branch_id = :branch_id and pb.created_at >= :start_day and  pb.created_at <= :end_day   ";
        $cmd =\Yii::$app->db->createCommand($sql);
        $cmd->bindValue(":branch_id", $branch_id)
            ->bindValue(":start_day", $start_day)
            ->bindValue(":end_day", $end_day);

        $row =  $cmd->queryOne(\PDO::FETCH_ASSOC);

        $sql_add_balance = "SELECT sum(amount) as amount FROM `poses_balance_detail` WHERE 
                                     `payment_method` = :payment_method and   created_at >= :start_day and  created_at <= :end_day   ";


        $cmd_add_balance =\Yii::$app->db->createCommand($sql_add_balance);
        $cmd_add_balance->bindValue(":payment_method", "Add balance")
            ->bindValue(":start_day", $start_day)
            ->bindValue(":end_day", $end_day);
        $row_add =  $cmd_add_balance->queryOne(\PDO::FETCH_ASSOC);





        return ["cash"=>number_format((float)($row["cash"]-$row_add["amount"]), 2, '.', ''),
            "card"=>number_format((float)($row["card"]), 2, '.', ''),
            "glovo"=>number_format((float)($row["glovo"]), 2, '.', ''),
            "glovo_cash"=>number_format((float)($row["glovo_cash"]), 2, '.', ''),
            "glovo_card"=>number_format((float)($row["glovo_card"]), 2, '.', ''),
            "wolt"=>number_format((float)($row["wolt_card"]), 2, '.', '')
        ];


    }

    public static function EditPosBalance($pos_id = null, $amount = 0) {

        $date = date("Y-m-d");

        $poses_balance = PosesBalance::find()->where(["poses_id"=>$pos_id])->andWhere(["like","created_at", $date."%", false])->andWhere(["end_time"=>null])->one();

        self::editSafeBalance($pos_id, $amount);

        if ($poses_balance){
            $poses_balance->cash =  ($poses_balance->cash + $amount);
        } else {
            $poses_balance = new PosesBalance();
            $poses_balance->start_time = date('Y-m-d H:i:s');
            $poses_balance->cash = $amount;
            $poses_balance->poses_id = $pos_id;
        }
        $poses_balance_detail = new PosesBalanceDetail();
        $poses_balance_detail->user_id = \Yii::$app->user->getId();
        $poses_balance_detail->pos_id = $pos_id;
        $poses_balance_detail->amount = $amount;
        $poses_balance_detail->payment_method = "Add balance";
        $poses_balance_detail->save();

        if ($poses_balance->save())
            return $poses_balance->cash;
        else return -1;


    }

    public static function EditPosBalanceByPos($pos_id = null, $amount = null, $action = null, $payment_method = null, $oder_id = null, $deliveryType = null) {

        $date = date("Y-m-d");

        $poses_balance = PosesBalance::find()->where(["poses_id"=>$pos_id])->andWhere(["end_time"=>null])->one();

        $method = strtolower($payment_method);

        if ($poses_balance){

            if (strtolower($deliveryType) == 'glovo') {
                if ($method == 'card')
                    $poses_balance->glovo_card = $poses_balance->glovo_card+$amount;
                if ($method == 'cash')
                    $poses_balance->glovo_cash = $poses_balance->glovo_cash+$amount;
                if ($method == 'transfer')
                    $poses_balance->glovo = $poses_balance->glovo+$amount;

            }
            else if (strtolower($deliveryType) == 'wolt') {
                if ($method == 'card')
                    $poses_balance->wolt_card = $poses_balance->wolt_card+$amount;

            }
            else  $poses_balance->$method =  ($poses_balance->$method + $amount);
        } else {
            $poses_balance = new PosesBalance();
        //    $poses_balance->$method = $amount;
            $poses_balance->poses_id = $pos_id;
            if (strtolower($deliveryType) == 'glovo') {
                if ($method == 'card')
                    $poses_balance->glovo_card = $amount;
                if ($method == 'cash')
                    $poses_balance->glovo_cash =$amount;
                if ($method == 'transfer')
                    $poses_balance->glovo =$amount;
            }
            else if (strtolower($deliveryType) == 'wolt') {
                if ($method == 'card')
                    $poses_balance->wolt_card =$amount;

            }
            else  $poses_balance->$method = $amount;


        }
        $poses_balance_detail = new PosesBalanceDetail();
        $poses_balance_detail->user_id = \Yii::$app->user->getId();
        $poses_balance_detail->pos_id = $pos_id;
        $poses_balance_detail->amount = $amount;
        $poses_balance_detail->order_id = $oder_id;
        $poses_balance_detail->action = $action;
        $poses_balance_detail->payment_method = $payment_method."-".$deliveryType;
        $poses_balance_detail->save();

        if ($poses_balance->save())
            return 1;
        else return -1;


    }

    public static function EditDriverBalanceByPos($driver_id = null, $amount = null, $action = null, $payment_method = null, $oder_id = null, $deliveryType = null) {

        $date = date("Y-m-d");

        $driver_balance = DriversBalance::find()->where(["driver_id"=>$driver_id])->andWhere(["like","created_at", $date."%", false])->andWhere(["end_time"=>null])->one();

        $method = strtolower($payment_method);

        if ($driver_balance){
            if ($method == "cash")
            $driver_balance->amount =  ($driver_balance->amount + $amount);
            else if ($method == "card")
                $driver_balance->card =  ($driver_balance->card + $amount);

        } else {
            $driver_balance = new DriversBalance();
            //    $poses_balance->$method = $amount;
            $driver_balance->driver_id = $driver_id;
            if ($method == "cash")
                $driver_balance->amount =  $amount;
            else if ($method == "card")
                $driver_balance->card =   $amount;


        }
        $driver_balance_detail = new DriversBalanceDetail();
        $driver_balance_detail->user_id = \Yii::$app->user->getId();
        $driver_balance_detail->amount = $amount;
        $driver_balance_detail->driver_id = $driver_id;
        $driver_balance_detail->action = $action.$method;
        $driver_balance_detail->save();

        if ($driver_balance->save())
            return 1;
        else return -1;


    }

    public static function editSafeBalance($pos_id = null , $amount = null) {

        $poses = Poses::find()->where(["id"=>$pos_id])->andWhere(["status"=>Status::getActive()])->one();
        $safe = Safe::find()->where(["branch_id"=>$poses["branch_id"]])->andWhere(["status"=>Status::getActive()])->one();

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
        $safe_balance_detail->pos_id = $pos_id;
        $safe_balance_detail->amount = -$amount;
        $safe_balance_detail->safe_id = $safe->id;
        $safe_balance_detail->payment = "Edit balance from pos";
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

    public static function getSafeDetail($day) {
        $day = explode("to", $day);
        $start_day = trim($day[0])." 00:00:00";
        $end_day = trim($day[1])." 23:59:59";

        $sql = "SELECT sbd.*, us.fullname as driver_name, p.name as pos_name, u.fullname as user_name  FROM safe_balance_detail sbd 
                left join user u ON u.id = sbd.user_id
                left join user us ON us.id = sbd.driver_id
                left join poses p ON p.id = sbd.pos_id
where sbd.created_at > '$start_day' and  sbd.created_at < '$end_day' ";

        $cmd =\Yii::$app->db->createCommand($sql);
        $row = $cmd->queryAll(\PDO::FETCH_ASSOC);

        return $row;
    }

    public static function dropBalanceFormSafe($safe_id = null, $amount = null, $bank_id=null, $comment= null) {

        $safe = Safe::find()->where(["id"=>$safe_id])->andWhere(["status"=>Status::getActive()])->one();
        $bank = Banks::find()->where(["id"=>$bank_id])->one();


        $safe->amount = $safe->amount+$amount;
        $safe->save();

        $date = date("Y-m-d");
        $safe_balance = SafeBalance::find()->where(["safe_id"=>$safe->id])->andWhere(["like","created_at", $date."%", false])->one();

        if ($safe_balance){
            $safe_balance->amount =  ($safe_balance->amount + $amount);
        } else {
            $safe_balance = new SafeBalance();
            $safe_balance->amount = -$amount;
            $safe_balance->safe_id = $safe->id;
        }

        $safe_balance_detail = new SafeBalanceDetail();
        $safe_balance_detail->user_id = \Yii::$app->user->getId();
        $safe_balance_detail->pos_id = 0;
        $safe_balance_detail->comment = $comment;
        $safe_balance_detail->bank_id = $bank_id;
        $safe_balance_detail->bank_name = $bank->name;
        $safe_balance_detail->amount = $amount;
        $safe_balance_detail->safe_id = $safe_id;
        $safe_balance_detail->payment = $amount<0 ? "Drop balance from safe" : "Add balance to safe";
        $safe_balance_detail->save();

        if ($safe_balance->save())
            return $safe_balance->amount;
        else return -1;
    }

    public static function dropExpenses($safe_id = null, $amount = null, $comment= null) {

        $safe = Safe::find()->where(["id"=>$safe_id])->andWhere(["status"=>Status::getActive()])->one();

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
        $safe_balance_detail->pos_id = 0;
        $safe_balance_detail->amount = $amount;
        $safe_balance_detail->safe_id = $safe_id;
        $safe_balance_detail->payment = "Expenses";
        $safe_balance_detail->comment = $comment;
        $safe_balance_detail->save();

        if ($safe_balance->save())
            return $safe_balance->amount;
        else return -1;
    }

    public static function GetMyBalance() {
        $user_id = \Yii::$app->user->getId();
        $day = date("Y-m-d");
        $pos = PosesToCashier::find()->where(["user_id"=>$user_id])->andWhere(["like","created_at", $day."%", false])
            ->orderBy(["id"=>SORT_DESC])->one();

        return PosesBalance::find()->where(["poses_id"=>$pos->id])->andWhere(["like","created_at", $day."%", false])->one();



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
        $driver_balance_detail->action = "by manager";
        $driver_balance_detail->save();

        if ($drivers_balance->save())
            return $drivers_balance->amount;
        else return -1;


    }

    public static function closeDay($pos_id = null, $driver_id= null, $diff_card = null,$diff_cash = null, $comment = null) {
        $end = date("Y-m-d"). "23:59:59";
        $start =  date('Y-m-d', strtotime('-1 day'))." 00:00:00";
        if ($pos_id>0) {
            $posTouser = PosesToCashier::find()->where(["pos_id"=>$pos_id])->orderBy(["id"=>SORT_DESC])->limit(1)->one();
            if ($posTouser) {

                $unpaid_orders = Orders::find()->where(["payment_method_id" => 4])->andWhere(["<>", "status", 10])
                    ->andWhere([">", "promise_date", $start])->andWhere(["<", "promise_date", $end])
                    ->andWhere(["user_id"=>$posTouser["user_id"]])
                    ->all();

                if ($unpaid_orders){
                    $res = "";
                    foreach($unpaid_orders as $uo) {
                        $res .= $uo["id"].",";
                    }
                    return "Paid this orders ".$res;

                }
            }
            $posesBalance = PosesBalance::find()->where(["poses_id"=>$pos_id])->andWhere(["end_time"=>null])->one();
            $posesBalance->end_time = date('Y-m-d H:i:s');
            $posesBalance->save();

            self::editSafeBalance($pos_id, -($posesBalance->cash+$posesBalance->glovo_cash + $diff_cash));
        }

        if ($driver_id>0) {

            $driverBalance = DriversBalance::find()->where(["driver_id"=>$driver_id])->andWhere(["end_time"=>null])->one();
            if($driverBalance) {
                $driverBalance->end_time = date('Y-m-d H:i:s');
                $driverBalance->save();

            }

            if (!$driverBalance) {
                $driverBalance = DriversBalance::find()->where(["driver_id"=>$driver_id])->orderBy(['id' => SORT_DESC])->limit(1)->one();
            }

         //   $timesheet = Timesheet::find()->where(["user_id"=>$driver_id])->andWhere(["end_date"=>""])->orderBy(['id' => SORT_DESC])->one();
//            if ($timesheet) {
//            $timesheet->end_date = time();
//            $timesheet->save();
//
//
//            $timesheet =  new Timesheet();
//            $timesheet->user_id = $driver_id;
//            $timesheet->state = "FINISH";
//            $timesheet->start_date = time();
//            $timesheet->save();
//            }
            DriverAction::editSafeBalance($driver_id, -($driverBalance->amount-$driverBalance->tip+$diff_cash));
        }

/*        if ($close_day){
            $close_day->diff_cash = $diff_cash;
            $close_day->diff_card = $diff_card;
            $close_day->driver_id = $driver_id;
            $close_day->pos_id = $pos_id;
            $close_day->comment = $comment;
            $close_day->user_id = \Yii::$app->user->getId();
        } else {*/
            $close_day = new CloseDay();
            $close_day->diff_cash = $diff_cash;
            $close_day->diff_card = $diff_card;
            $close_day->driver_id = $driver_id;
            $close_day->pos_id = $pos_id;
            $close_day->comment = $comment;
            $close_day->user_id = \Yii::$app->user->getId();
       /* }*/

        if ($close_day->save())
            return Result::SUCCESS;
        else return Result::FAILURE;


    }

    public static function getCloseDay($day) {
        $day = explode("to", $day);
        $start_day = trim($day[0])." 00:00:00";
        $end_day = trim($day[1])." 23:59:59";

        $sql = "SELECT cd.*, u.id as user_id, u.username as username, p.id as pos_id, p.name as pos_name FROM close_day cd 
                    left join user u ON u.id = cd.driver_id
                    left join poses p ON p.id = cd.pos_id 
                WHERE cd.created_at > '$start_day' and cd.created_at < '$end_day' ";
        return \Yii::$app->db->createCommand($sql)->queryAll(PDO::FETCH_ASSOC);
    }

    public static function paymentMethods() {
        return PaymentMethods::find()->where(["status"=>Status::getActive()])->all();
    }

    public static function deliveryMethods() {
        return DeliveryMethods::find()->where(["status"=>Status::getActive()])->all();
    }

    public static function OrderStatuses() {
        return OrderStatus::find()->where(["status"=>Status::getActive()])->all();
    }

    public static function checkPin($pin = null) {


        $sql = "SELECT a.item_name FROM user u 
                left join auth_assignment a ON a.user_id = u.id
                WHERE u.pin = :pin;";

        $cmd =  \Yii::$app->db->createCommand($sql)
            ->bindValue(":pin", $pin)
            ->queryOne(PDO::FETCH_ASSOC);
        if (trim($cmd["item_name"]) == "viceManager" || trim($cmd["item_name"]) == "branchManager" || trim($cmd["item_name"]) == "manager" || $cmd["item_name"] == "admin")
            return true;
        else return  "This user havn't permissions";

    }






}