<?php
/**
 * Created by PhpStorm.
 * User: levan
 * Date: 4/13/2020
 * Time: 12:42
 */

namespace api\actions;


use api\models\database\changeDriver;
use api\models\database\Customers;
use api\models\database\DeliveryMethods;
use api\models\database\DriversBalance;
use api\models\database\DriversBalanceDetail;
use api\models\database\OrderActions;
use api\models\database\Orders;
use api\models\database\OrderStatus;
use api\models\database\PaymentMethods;
use api\models\database\Status;
use api\models\database\Timesheet;
use api\models\response\Result;
use Automattic\WooCommerce\Client;
use Yii;
use yii\db\Exception;

use mdm\admin\models\User;
use yii\helpers\Json;

class OrdersActions
{
    public static function OrdersList($status = null, $day = false) {
        if ($day) {
            $day = explode("to",$day);
        }

        $sql_stat = "SELECT  * FROM orders 
                        where branch = :branch AND status IN ($status)";

        if ($day) {
            $sql_stat .= " AND (created_at >= :start_day AND created_at <= :end_day AND promise_date is null)
                             OR (promise_date >= :start_day AND promise_date <= :end_day)";
        } else {
            $sql_stat .= " AND created_at like '".date("Y-m-d")."%' AND  (promise_date is null OR (promise_date like '".date("Y-m-d")."%'))";
        }

        $cmd = \Yii::$app->db->createCommand($sql_stat)
            ->bindValue(":branch", Yii::$app->user->identity->branch);

        if ($day) {
            $cmd->bindValue(":start_day", trim($day[0])." 00:00:00")
                ->bindValue(":end_day",trim($day[1])." 23:59:59");
        }
        /*
            ->bindValue(":start_date", trim($day[0]))
            ->bindValue(":end_date", trim($day[1]));*/

        $rows_stat = $cmd->queryAll(\PDO::FETCH_ASSOC);
        $result = [];
        foreach ($rows_stat as $row) {
            $row["order_data"] = json_decode($row["order_data"]);
            $result[] = $row;
        }

        return $result;

    }

    public static function getGetOrders($branch = null){



        $woocommerce = new Client(
            'https://www.ronnyspizza.com',
            'ck_a696a04e96b0b1caaacefdd947395303ee8b6f69',
            'cs_b49750d7d0d4b680527c07993e2bd006afeb56a8',
            [
                'version' => 'wc/v3',
            ]
        );

        $data = [
            'status' => ['on-hold','processing']
        ];
        $d = date('Y-m-d H:i:s', strtotime('+115 minutes'));
        return [
            "woocommerce"=>$woocommerce->get("orders", $data),
            "legacy"=>Orders::find()->where(["branch"=>$branch])->andWhere(["status"=>0])->andWhere([">","created_at",$d])->all(),
            "date"=> $d
        ];
    }

    public static function getGetDeliveryOrders($branch = null){

        $d =  date('Y-m-d');
        return \api\models\database\Orders::find()->where(["branch"=>$branch])->andWhere(["status"=>OrderStatus::getInDelivery()])->andWhere(["like","order_data",'%"deliveryMethod":"Delivery"%',false])->andWhere(["like","created_at", $d."%",false])->all();
    }

    public static function ordersByDriver($driver_id = null, $day = false){


        if ($day) {
            $day = explode("to",$day);
        }

        $sql_stat = "SELECT * FROM orders where driver_id = :driver_id ";

        if ($day) {
            $sql_stat .= " AND ((created_at >= :start_day AND created_at <= :end_day AND promise_date is null)
                             OR (promise_date >= :start_day AND promise_date <= :end_day))";
        } else {
            $sql_stat .= " AND created_at like '".date("Y-m-d")."%' AND  (promise_date is null OR (promise_date like '".date("Y-m-d")."%'))";
        }

        $cmd = \Yii::$app->db->createCommand($sql_stat)
            ->bindValue(":driver_id", $driver_id);

        if ($day) {
            $cmd->bindValue(":start_day", trim($day[0])." 00:00:00")
                ->bindValue(":end_day",trim($day[1])." 23:59:59");
        }


        $rows_stat = $cmd->queryAll(\PDO::FETCH_ASSOC);
   /*     $result = [];
        foreach ($rows_stat as $row) {
            $row["order_data"] = json_decode($row["order_data"]);
            $result[] = $row;
        }*/

        return $rows_stat;
    }

    public static function getGetOrdersNewForPos($branch = null){

        $woocommerce = new Client(
            'https://www.ronnyspizza.com',
            'ck_a696a04e96b0b1caaacefdd947395303ee8b6f69',
            'cs_b49750d7d0d4b680527c07993e2bd006afeb56a8',
            [
                'version' => 'wc/v3',
            ]
        );

        $data = [
            'status' => ['on-hold','processing']
        ];
        return [
            "woocommerce"=>$woocommerce->get("orders", $data),
            "legacy"=>\api\models\database\Orders::find()->where(["branch"=>$branch])->where(["status"=>0])->all()
        ];

    }

    public static function attachOrderToDriver($order_ids = null, $driver_id= null)
    {
        $transaction = \Yii::$app->db->beginTransaction();

        try {

            $timesheet = Timesheet::find()->where(["user_id"=>$driver_id])->andWhere(["state"=>"IN"])->
                orderBy(["created_at"=>SORT_DESC])->limit(1)->one();

            $driver_balance = DriversBalanceDetail::find()->where(["driver_id"=>$driver_id])->
                andWhere([">=","created_at",$timesheet->created_at])->andWhere(["action"=>"Add balance"])
                ->andWhere([">","amount",0])->one();

            if (!$driver_balance)
            {
                DriverAction::EditDriverBalance($driver_id, 85);
            }

            $order_ids = explode("," ,$order_ids);
            foreach ($order_ids as $oder_id) {
                $orders = Orders::find()->where(["id"=>$oder_id])->one();
                $orders->status = OrderStatus::getInDelivery();
                $orders->driver_id = $driver_id;
                $orders->start_delivery = date('Y-m-d H:i:s');
                $orders->save();
            }



                $transaction->commit();

                return true;


        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }

        return false;

    }

    public static function finishOrderDelivery($driver_id = null, $order_id= null, $payment_method = null, $split_card =null, $split_cash = null, $tip = 0)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        try {


            $orders = Orders::find()->where(["id"=>$order_id])->andWhere(["status"=>OrderStatus::getInDelivery()])->one();


                $data = Json::decode($orders->order_data);

             /*   if ($orders->source == "woocommerce") {

                    $payment_method = "CASH";
                    $amount = $data["total"];
                }
                else if ($orders->source == "Legacy") {


                    if ($data[0]["payment"] == "CASH")
                        $payment_method = "CASH";

                    $amount = $data[0]["total_price"];
                }

                else */

            if ($orders->source == "pos"){
                    if ( (strtolower($payment_method) == "cash" || strtolower($payment_method) == "card") && ($data["deliveryType"] == "ronnys" || strtolower($data["deliveryType"]) == "delivery")) {
                        if (isset($data["discPrice"]) && $data["discPrice"]>0) {
                            if ($data["discountName"]=="Diplomat") {
                                $amount = $data["totalPrice"]/1.18;
                            } else
                            if ($data["discountAmount"])
                                $amount = $data["totalPrice"]-$data["discount"];
                            else
                                $amount = $data["totalPrice"]-($data["totalPrice"]*$data["discount"]/100);
                        }
                        else $amount =  $data["totalPrice"];

                    }


            }

                $data["paymentType"] = $payment_method;
                $data["splitCard"] = $split_card;
                $data["splitCash"] = $split_cash;
                $orders->order_data = json_encode($data);
                $orders->status = OrderStatus::getDelivered();

                $orders->end_delivery = date('Y-m-d H:i:s');
                $orders->end_delivery = date('Y-m-d H:i:s');


                $date = date("Y-m-d");

                if ($orders->payment_method_id == 4) {

                        $driver_balance = DriversBalance::find()->where(["driver_id"=>$driver_id])->andWhere(["end_time"=>null])->one();
                        if ($driver_balance) {
                            $driver_balance->tip = $driver_balance->tip+$tip;
                            if (strtolower($payment_method) == "cash")
                                $driver_balance->amount = $driver_balance->amount + $amount;
                            if (strtolower($payment_method) == "card")
                                $driver_balance->card = $driver_balance->card + $amount;
                            if (strtolower($payment_method) == "split") {
                                $driver_balance->amount = $driver_balance->amount + $split_cash;
                                $driver_balance->card = $driver_balance->card +  $split_card;
                            }
                        } else {
                            $driver_balance = new DriversBalance();
                            $driver_balance->tip = $tip;
                            $driver_balance->driver_id = $driver_id;
                            if (strtolower($payment_method) == "cash")
                                $driver_balance->amount = $amount;
                            if (strtolower($payment_method) == "card")
                                $driver_balance->card = $amount;
                            if (strtolower($payment_method) == "split") {
                                $driver_balance->amount =  $split_cash;
                                $driver_balance->card =   $split_card;
                            }
                        }
                        $driver_balance->save();

                        if (strtolower($payment_method) == "split") {
                            $driver_balance_detail = new DriversBalanceDetail();
                            $driver_balance_detail->tip = $tip;
                            $driver_balance_detail->user_id = Yii::$app->user->getId();
                            $driver_balance_detail->driver_id = $driver_id;
                            $driver_balance_detail->amount = $split_cash;
                            $driver_balance_detail->action = $payment_method."-cash";
                            $driver_balance_detail->save();
                            $driver_balance_detail = new DriversBalanceDetail();
                            $driver_balance_detail->user_id = Yii::$app->user->getId();
                            $driver_balance_detail->driver_id = $driver_id;
                            $driver_balance_detail->amount = $split_card."-card";
                            $driver_balance_detail->action = $payment_method;
                            $driver_balance_detail->save();
                        } else {
                            $driver_balance_detail = new DriversBalanceDetail();
                            $driver_balance_detail->tip = $tip;
                            $driver_balance_detail->user_id = Yii::$app->user->getId();
                            $driver_balance_detail->driver_id = $driver_id;
                            $driver_balance_detail->amount = $amount;
                            $driver_balance_detail->action = $payment_method;
                            $driver_balance_detail->save();
                        }




                }


                $orders->payment_method_id = PaymentMethods::getStatusIdByKey($payment_method);
                $orders->save();


                $transaction->commit();

                return Result::SUCCESS;

        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }

        return Result::FAILURE;

    }

    public static function cancelOrder($oder_id = null, $text =null, $user = null, $mail = null, $status = 5){

        $branch = userActions::getBranch();

        $woocommerce = new Client(
            'https://www.ronnyspizza.com',
            'ck_a696a04e96b0b1caaacefdd947395303ee8b6f69',
            'cs_b49750d7d0d4b680527c07993e2bd006afeb56a8',
            [
                'version' => 'wc/v3',
            ]
        );

        $transaction = \Yii::$app->db->beginTransaction();

        try {

            $order_data = $woocommerce->get('orders/'.$oder_id);
            $orders = new Orders();
            $orders->order_id = $oder_id;
            $orders->branch = $branch;
            $orders->status = $status;
            $orders->user_id = \Yii::$app->user->getId();
            $orders->order_data = Json::encode($order_data);



            if ($orders->save()) {
                $transaction->commit();

                $data = [
                    'status' => 'cancelled'
                ];

                $woocommerce->put('orders/'.$oder_id.'', $data);


                Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => 'cancelOrder-html', 'text' => 'cancelOrder-text'],
                        ['username' => $user, 'text' => $text]

                    )
                    ->setFrom([Yii::$app->params['senderEmail'] => 'ronnys.ge/site'])
                    ->setTo($mail)
                    ->setSubject('Your order canceled')
                    ->send();

                return Result::SUCCESS;
            }

        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }

        return Result::FAILURE;

    }

    public static function changeOrderAddress($oder_id = null, $method_id, $address = null){


        $woocommerce = new Client(
            'https://www.ronnyspizza.com',
            'ck_a696a04e96b0b1caaacefdd947395303ee8b6f69',
            'cs_b49750d7d0d4b680527c07993e2bd006afeb56a8',
            [
                'version' => 'wc/v3',
            ]
        );



        $data = [


            'shipping_lines' => [0=>[
                'id' => $method_id,
                'method_title' => $address,

            ]]



        ];

        if ($woocommerce->put('orders/'.$oder_id, $data))
            return Result::SUCCESS;
        else return Result::FAILURE;




    }

    public static function updateOrderStatus($oder_id = null, $status = 1,  $duration = null){

        $branch = userActions::getBranch();

        switch($status){
            case 1:
                $label = 'pending';
                break;
            case 4:
                $label = 'completed';
                break;
            case 5:
                $label = 'cancelled';
                break;
            default:
                $label = null;
        }


        $woocommerce = new Client(
            'https://www.ronnyspizza.com',
            'ck_a696a04e96b0b1caaacefdd947395303ee8b6f69',
            'cs_b49750d7d0d4b680527c07993e2bd006afeb56a8',
            [
                'version' => 'wc/v3',
            ]
        );

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            if ($status == 1 || $status == 5) {
                $order_data = $woocommerce->get('orders/'.$oder_id);
                $orders = Orders::find()->where(["order_id"=>$oder_id])->one();
                if (!$orders) {
                    $orders = new Orders();
                    $orders->order_id = $oder_id;
                    $orders->branch = $branch;
                    $orders->duration = $duration?$duration:0;
                    $orders->status = $status;
                    $orders->user_id = \Yii::$app->user->getId();
                    $orders->order_data = Json::encode($order_data);
                }

            } else {
                $orders = Orders::find()->where(["id"=>$oder_id])->one();
                $orders->status = $status;
                $oder_id = $orders->order_id;
            }
            if ($status == 5) {
                Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => 'cancelOrder-html', 'text' => 'cancelOrder-text'],
                        ['username' => 'Levan']

                    )
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                    ->setTo("levan@gmail.com")
                    ->setSubject('Password reset for ' . Yii::$app->name)
                    ->send();
            }




            if ($orders->save()) {
                $transaction->commit();
                $m = Orders::find()->where(["order_id"=>$oder_id])->one();
                if ($m["source"] == "woocommerce") {
                    $data = ['status' => $label];

                    $woocommerce->put('orders/'.$oder_id, $data);
                }





                return Result::SUCCESS;
            }

        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }

        return Result::FAILURE;

    }

    public static function getOrdersBacker($branch = null, $status = null, $created = null){

        $status = explode(",",$status);
        $branch = explode(",",$branch);

        if ($created) {
            return Json::encode(Orders::find()->where(["branch"=>$branch])->andWhere(["status"=>$status])->andWhere(['like', 'created_at',  $created.'%', false])->orderBy(['id' => SORT_DESC])->all());
        }

        return Json::encode(Orders::find()->where(["branch"=>$branch])->andWhere(["status"=>$status])->orderBy(['id' => SORT_DESC])->all());

    }

    public static function getCurrentOrdersForPos($branch = null, $status = null, $created = null){
        $status = explode(",",$status);
        $branch = explode(",",$branch);

        if ($created) {
            return \api\models\database\Orders::find()->where(["branch"=>$branch])->andWhere(["status"=>$status])->andWhere(['like', 'created_at',  $created.'%', false])->orderBy(['id' => SORT_DESC])->all();
        }

        return \api\models\database\Orders::find()->where(["branch"=>$branch])->andWhere(["status"=>$status])->orderBy(['id' => SORT_DESC])->all();

    }

    public static function updateOrder($oder_id = 0, $status = 0){

        $oreder = Orders::find()->where(["id"=>$oder_id])->one();

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            if ($status == 2)
                $oreder->accept_date = date('Y-m-d H:i:s');
            if ($status == 3)
                $oreder->finish_date = date('Y-m-d H:i:s');
            $oreder->status = $status;
            $oreder->backer_id = \Yii::$app->user->getId();
            $oreder->save();



            $transaction->commit();

            return Result::SUCCESS;
        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }

        return Result::FAILURE;

    }

    public static function createOrder($data = null, $source = null) {

        $order_data = \Opis\Closure\unserialize($data);


        $order_id =  $order_data["orderId"];
        $status = OrderStatus::getInkitchen();

        $add_id = 0;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (isset($order_data["id"]) && $order_data["id"]==0) {
                $order = new Orders();
                $order->order_data	 =  Json::encode($order_data);
                $order->source = $source;
                $order->pos_id = $order_data["pos_id"];
                $order->status = $status;
                $order->is_discounted = $order_data["discount"]>0?true:false;
                $order->created_at = date("Y-m-d H:i:s");
                $order->delivery_method_id = DeliveryMethods::getStatusIdByKey($order_data["deliveryType"]);
                $order->payment_method_id = PaymentMethods::getStatusIdByKey($order_data["paymentType"]);
                $order->duration = isset($order_data["promise_time"])?$order_data["promise_time"]:15;
                if ($order_data["isFuture"])
                    $order->promise_date = $order_data["date"];
                else $order->promise_date = date("Y-m-d H:i:s", strtotime( (isset($order_data["promise_time"])?$order_data["promise_time"]:15)." minutes"));
                $order->order_id = $order_id;
                $order->branch = Yii::$app->user->identity->branch;
                $order->user_id = \Yii::$app->user->getId();
                $order->save();
                if (strtolower($order_data["paymentType"]) == "cash" || strtolower($order_data["paymentType"]) == "card" ||   strtolower($order_data["paymentType"]) == "transfer") {

                    if ($order_data["discount"]>0) {
                        if ($order_data["discountName"]=="Diplomat") {
                            $price = $order_data["totalPrice"]/1.18;
                        } else
                        if ($order_data["discountAmount"])
                            $price = $order_data["totalPrice"]-$order_data["discount"];
                        else
                            $price = $order_data["totalPrice"]-($order_data["totalPrice"]*$order_data["discount"]/100);
                    }
                        else $price =  $order_data["totalPrice"];

                    PosesAction::EditPosBalanceByPos($order_data["pos_id"], $price, "New order", $order_data["paymentType"], $order->id, $order_data["deliveryType"]);

                }
                else if(strtolower($order_data["paymentType"]) == "split") {
                    PosesAction::EditPosBalanceByPos($order_data["pos_id"], $order_data["splitCard"], "New order Split", 'card', $order->id, $order_data["deliveryType"]);
                    PosesAction::EditPosBalanceByPos($order_data["pos_id"], $order_data["splitCash"], "New order Split", "cash", $order->id, $order_data["deliveryType"]);

                }

                if (isset($order_data["customer"]) && $order_data["customer"]["phone"] && isset($order_data["invoice"])) {
                    $customers = Customers::find()->andFilterWhere(['like', 'tel', "%".$order_data["customer"]["phone"]."%", false])->one();
                    if($customers) {
                        $customers->invoice = serialize($order_data["invoice"]);
                        $customers->save();
                    }
                }

                $add_id =  $order->id;


            }
            $transaction->commit();
            return $add_id;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function createOrderForWeb($data = null, $source = null) {

        $order_data = \Opis\Closure\unserialize($data);


        $order_id =  0;
        $status = OrderStatus::getPending();

        $add_id = 0;
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            if (isset($order_data["id"]) && $order_data["id"]==0) {
                $order = new Orders();
                $order->order_data	 =  Json::encode($order_data);
                $order->source = $source;
                $order->pos_id = 6;
                $order->status = $status;
                $order->is_discounted = $order_data["discount"]>0?true:false;
                $order->created_at = date("Y-m-d H:i:s");
                $order->delivery_method_id = DeliveryMethods::getStatusIdByKey($order_data["deliveryType"]);
                $order->payment_method_id = PaymentMethods::getStatusIdByKey($order_data["paymentType"]);
//                $order->duration = isset($order_data["promise_time"])?$order_data["promise_time"]:15;
//                if ($order_data["isFuture"])
//                    $order->promise_date = $order_data["date"];
//                else $order->promise_date = date("Y-m-d H:i:s", strtotime( (isset($order_data["promise_time"])?$order_data["promise_time"]:15)." minutes"));
                $order->order_id = $order_id;
                $order->branch = "digomi";
                $order->user_id = \Yii::$app->user->getId();
                $order->save();
                if (strtolower($order_data["paymentType"]) == "cash" || strtolower($order_data["paymentType"]) == "card" ||   strtolower($order_data["paymentType"]) == "transfer") {

                    if ($order_data["discount"]>0) {
                        if ($order_data["discountName"]=="Diplomat") {
                            $price = $order_data["totalPrice"]/1.18;
                        } else
                            if ($order_data["discountAmount"])
                                $price = $order_data["totalPrice"]-$order_data["discount"];
                            else
                                $price = $order_data["totalPrice"]-($order_data["totalPrice"]*$order_data["discount"]/100);
                    }
                    else $price =  $order_data["totalPrice"];

                    PosesAction::EditPosBalanceByPos(6, $price, "New order", $order_data["paymentType"], $order->id, $order_data["deliveryType"]);

                }
                else if(strtolower($order_data["paymentType"]) == "split") {
                    PosesAction::EditPosBalanceByPos(6, $order_data["splitCard"], "New order Split", 'card', $order->id, $order_data["deliveryType"]);
                    PosesAction::EditPosBalanceByPos(6, $order_data["splitCash"], "New order Split", "cash", $order->id, $order_data["deliveryType"]);

                }

                if (isset($order_data["customer"]) && $order_data["customer"]["phone"] && isset($order_data["invoice"])) {
                    $customers = Customers::find()->andFilterWhere(['like', 'tel', "%".$order_data["customer"]["phone"]."%", false])->one();
                    if($customers) {
                        $customers->invoice = serialize($order_data["invoice"]);
                        $customers->save();
                    }
                }

                $add_id =  $order->id;


            }
            $transaction->commit();
            return $add_id;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function createWaste($data = null, $source = null) {

        $order_data = \Opis\Closure\unserialize($data);



        $status = OrderStatus::getWaste();

        $add_id = 0;
        $transaction = \Yii::$app->db->beginTransaction();
        try {

                $order = new Orders();
                $order->order_data	 =  Json::encode($order_data);
                $order->source = $source;
                $order->status = $status;
                $order->created_at =date("Y-m-d H:i:s");
                $order->branch = Yii::$app->user->identity->branch;
                $order->user_id = \Yii::$app->user->getId();
                $order->save();
                $add_id =  $order->id;

            $transaction->commit();
            return $add_id;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function changeStatus($id = null, $status = null) {


        $status = OrderStatus::getStatusIdByKey($status);


        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $order = Orders::find()->where(["id"=>$id])->one();
            if (strtolower(OrderStatus::getStatusNameById($order->status)) == "refund" || strtolower(OrderStatus::getStatusNameById($order->status)) == "void") {
                return "Couldn't change refunded or voided status";
            }

            if ($status==7) {
                if($order->payment_method_id ==4)
                return "Couldn't finish unpaid order";
            }

            if($status == 10) {
                return "Operation forbidden";
            }

            if ($order->status != $status) {

                $order->status = $status;
                $order->save();
                $transaction->commit();
                return $order->id;
            } return "Order already has this status";

        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }


    public static function editOrder($new_order = null) {

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $new_data = \Opis\Closure\unserialize($new_order);

            $id =  $new_data["id"];

            $old_order = Orders::find()->where(["id"=>$id])->one();


            $old_data = \yii\helpers\Json::decode($old_order["order_data"]);


            if (strtolower(OrderStatus::getStatusNameById($old_order->status)) == "refund" || strtolower(OrderStatus::getStatusNameById($old_order->status)) == "void") {
                return "Couldn't change refunded or voided status";
            }


            $old_price = 0;

            if ($old_data["discount"]>0) {
                if ($old_data["discountName"]=="Diplomat") {
                    $old_price = $old_data["totalPrice"]/1.18;
                } else
                    if ($old_data["discountAmount"])
                        $old_price = $old_data["totalPrice"]-$old_data["discount"];
                    else
                        $old_price = $old_data["totalPrice"]-($old_data["totalPrice"]*$old_data["discount"]/100);
            }
            else $old_price =  $old_data["totalPrice"];

            if(strtolower($old_data["paymentType"]) == "split") {
                $old_data["paymentType"] = "cash";
                $old_price = intval($old_data["splitCard"])+$old_data["splitCash"];
            }




            $new_price = 0;

            if ($new_data["discount"]>0) {
                if ($new_data["discountName"]=="Diplomat") {
                    $new_price = $new_data["totalPrice"]/1.18;
                } else
                    if ($new_data["discountAmount"])
                        $new_price = $new_data["totalPrice"]-$new_data["discount"];
                    else
                        $new_price = $new_data["totalPrice"]-($new_data["totalPrice"]*$new_data["discount"]/100);
            }
            else $new_price =  $new_data["totalPrice"];

            if(strtolower($new_data["paymentType"]) == "split") {
                $new_data["paymentType"] = "cash";
                $new_price = intval($new_data["splitCard"])+$new_data["splitCash"];
            }




            $changed_amount = floatval($new_price)-floatval($old_price);

//            if ($changed_amount <> 0 && $old_order->payment_method_id == 7) {
//                return  "Could'n change finished orders items";
//            }

             $action = "Edit order";

            if ($changed_amount < 0)
                $action = "Edit - Refund";
            else if ($changed_amount > 0) {
                $action = "Edit - Add money";

            }



            if ($old_order->payment_method_id != 3 && $old_order->payment_method_id != 4)
                {
                if ($old_order->driver_id > 0) {
                    $result = PosesAction::EditDriverBalanceByPos($old_order->driver_id, (-1*$old_price), "edt . ".$action, $old_data["paymentType"], $id, $old_data["deliveryType"]);
                }
                else
                $result = PosesAction::EditPosBalanceByPos($new_data["pos_id"], (-1*$old_price), "edt . ".$action, $old_data["paymentType"], $id, $old_data["deliveryType"]);
                    $order_action = new OrderActions();
                    $order_action->action = $action;
                    $order_action->order_id = $id;
                    $order_action->user_id = \Yii::$app->user->getId();
                    $order_action->data = Json::encode($old_data);
                    $order_action->save();
                }


            if (PaymentMethods::getStatusIdByKey($new_data["paymentType"])!= 3 && PaymentMethods::getStatusIdByKey($new_data["paymentType"]) != 4) {
                if ($old_order->driver_id > 0) {
                    $result = PosesAction::EditDriverBalanceByPos($old_order->driver_id, $new_price, "Edit order.".$action, $new_data["paymentType"], $id, $old_data["deliveryType"]);
                } else
                $result = PosesAction::EditPosBalanceByPos($new_data["pos_id"], $new_price, "Edit order. ".$action, $new_data["paymentType"], $id, $new_data["deliveryType"]);

            }
            $old_order->delivery_method_id = DeliveryMethods::getStatusIdByKey($new_data["deliveryType"]);
            $old_order->payment_method_id = PaymentMethods::getStatusIdByKey($new_data["paymentType"]);
            $old_order->order_data = Json::encode($new_data);
            $old_order->user_id = \Yii::$app->user->getId();
            $old_order->reopen = true;
            $old_order->save();

            $transaction->commit();
            return  "Edit Successfully";
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }



    public static function reopen($new_order = null) {

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $new_data = \Opis\Closure\unserialize($new_order);


            $id =  $new_data["id"];

            $order = Orders::find()->where(["id"=>$id])->one();

            $old_data = \yii\helpers\Json::decode($order["order_data"]);

            $old_price = 0;
            if ($old_data["discount"]>0) {
                if ($old_data["discountName"]=="Diplomat") {
                    $old_price = $old_data["totalPrice"]/1.18;
                } else
                    if ($old_data["discountAmount"])
                        $old_price = $old_data["totalPrice"]-$old_data["discount"];
                    else
                        $old_price = $old_data["totalPrice"]-($old_data["totalPrice"]*$old_data["discount"]/100);
            }
            else $old_price =  $old_data["totalPrice"];



            $new_price = 0;

            if ($new_data["discount"]>0) {
                if ($new_data["discountName"]=="Diplomat") {
                    $new_price = $new_data["totalPrice"]/1.18;
                } else
                    if ($new_data["discountAmount"])
                        $new_price = $new_data["totalPrice"]-$new_data["discount"];
                    else
                        $new_price = $new_data["totalPrice"]-($new_data["totalPrice"]*$new_data["discount"]/100);
            }
            else $new_price =  $new_data["totalPrice"];


            $changed_amount = floatval($new_price)-floatval($old_price);

            $result = null;
            if ($changed_amount < 0)
                $action = "Refund";
            else if ($changed_amount > 0) {
                $action = "Add money";

            }
            if ($order->payment_method_id != 3 && $order->payment_method_id != 4)
                if ($changed_amount != 0) {
                $result = PosesAction::EditPosBalanceByPos($new_data["pos_id"], $changed_amount, "Reopen order.$action", $new_data["paymentType"], $id, $new_data["deliveryType"]);
                $order_action = new OrderActions();
                $order_action->action = $action;
                $order_action->order_id = $id;
                $order_action->user_id = \Yii::$app->user->getId();
                $order_action->data = $order->order_data;
                $order_action->save();
            }

            $order->order_data = Json::encode($new_data);
            $order->user_id = \Yii::$app->user->getId();
            $order->reopen = true;
            $order->save();

            $transaction->commit();
            return  "Reopen successfully";
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function refund($id = null,  $pos_id = null) {

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $order = Orders::find()->where(["id"=>$id])->one();
            $order_data = \yii\helpers\Json::decode($order["order_data"]);

            if (PaymentMethods::getPayLayter() == $order->payment_method_id || PaymentMethods::getInvoice() == $order->payment_method_id) {
                return "Operation forbidden";
            }


            if (strtolower(OrderStatus::getStatusNameById($order->status)) == "refund") {
                return "Order already refunded";
            }

            if ($order_data["discount"]>0) {
                if ($order_data["discountName"]=="Diplomat") {
                    $price = $order_data["totalPrice"]/1.18;
                } else
                    if ($order_data["discountAmount"])
                        $price = $order_data["totalPrice"]-$order_data["discount"];
                    else
                        $price = $order_data["totalPrice"]-($order_data["totalPrice"]*$order_data["discount"]/100);
            }
            else $price =  $order_data["totalPrice"];

            if(strtolower($order_data["paymentType"]) == "split") {
                $order_data["paymentType"] = "cash";
                $price = intval($order_data["splitCard"])+$order_data["splitCash"];
            }

            $result = PosesAction::EditPosBalanceByPos($pos_id, (-1*$price), "Refund", $order_data["paymentType"], $id, $order_data["deliveryType"]);

            $order_action = new OrderActions();
            $order_action->action = "Refund";
            $order_action->order_id = $id;
            $order_action->user_id = \Yii::$app->user->getId();
            $order_action->data = $order->order_data;
            $order_action->save();

            $order->user_id = \Yii::$app->user->getId();
            $order->status = OrderStatus::getStatusIdByKey("refund");
            $order->save();

            $transaction->commit();
            return  "Edited successfully";
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function EditOrderForDriver($id=null, $driver_id=null, $payment_method=null, $split_cash = null, $split_card = null) {

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $order = Orders::find()->where(["id"=>$id])->one();

            $data = \yii\helpers\Json::decode($order["order_data"]);
            if($payment_method=="false" && $order->payment_method_id > 6) {
                return "Operation Forbidden";
            }
            if ($payment_method=="false") {
                $logireba = new changeDriver();
                $logireba->driver_id = $driver_id;
                $logireba->old_driver_id = $order->driver_id;
                $logireba->user_id = Yii::$app->user->getId();
                $logireba->action = "Change driver";
                $logireba->order_id = $order->id;
                $logireba->save();

                if (PaymentMethods::getPayLayter() == $order->payment_method_id || PaymentMethods::getInvoice() == $order->payment_method_id) {
                    $order->driver_id = $driver_id;
                }



            }


            if ($payment_method !="false") {

                if ($order->status == 7 &&
                    ($order->payment_method_id == PaymentMethods::getCard() || $order->payment_method_id == PaymentMethods::getSplit() || $order->payment_method_id == PaymentMethods::getCash())) {


                    if ( (strtolower($payment_method) == "cash" || strtolower($payment_method) == "card") && ($data["deliveryType"] == "ronnys" || strtolower($data["deliveryType"]) == "delivery")) {
                        if ($data["discount"]>0) {
                            if ($data["discountName"]=="Diplomat") {
                                $amount = $data["totalPrice"]/1.18;
                            } else
                                if ($data["discountAmount"])
                                    $amount = $data["totalPrice"]-$data["discount"];
                                else
                                    $amount = $data["totalPrice"]-($data["totalPrice"]*$data["discount"]/100);
                        }
                        else $amount =  $data["totalPrice"];
                    }


                    $data["paymentType"] = $payment_method;
                    $data["splitCard"] = $split_card;
                    $data["splitCash"] = $split_cash;
                    $order->order_data = Json::encode($data);

                    $driver_balance = DriversBalance::find()->where(["driver_id"=>$driver_id])->andWhere(["end_time"=>null])->one();
                    if ($driver_balance) {

                        //   || $order->payment_method_id == PaymentMethods::getSplit() || $order->payment_method_id == PaymentMethods::getCash())
                        if($order->payment_method_id == PaymentMethods::getCash())
                            $driver_balance->amount = $driver_balance->amount - $amount;
                        if($order->payment_method_id == PaymentMethods::getCard())
                            $driver_balance->card = $driver_balance->card - $amount;

                        $driver_balance->save();

                        if (strtolower($payment_method) == "cash")
                            $driver_balance->amount = $driver_balance->amount + $amount;
                        if (strtolower($payment_method) == "card")
                            $driver_balance->card = $driver_balance->card + $amount;
                        if (strtolower($payment_method) == "split") {
                            $driver_balance->amount = $driver_balance->amount + $split_cash;
                            $driver_balance->card = $driver_balance->card +  $split_card;
                        }

                        $driver_balance->save();
                    }



                    if (strtolower($payment_method) == "split") {

                        $driver_balance_detail = new DriversBalanceDetail();
                        $driver_balance_detail->user_id = Yii::$app->user->getId();
                        $driver_balance_detail->driver_id = $driver_id;
                        $driver_balance_detail->amount = $split_cash;
                        $driver_balance_detail->action = $payment_method."-cash edit";
                        $driver_balance_detail->save();
                        $driver_balance_detail = new DriversBalanceDetail();
                        $driver_balance_detail->user_id = Yii::$app->user->getId();
                        $driver_balance_detail->driver_id = $driver_id;
                        $driver_balance_detail->amount = $split_card;
                        $driver_balance_detail->action = $payment_method."-card edit";
                        $driver_balance_detail->save();


                        $driver_balance_detail = new DriversBalanceDetail();
                        $driver_balance_detail->user_id = Yii::$app->user->getId();
                        $driver_balance_detail->driver_id = $driver_id;
                        $driver_balance_detail->amount = -$order->splitCash;
                        $driver_balance_detail->action = $payment_method."-cash";
                        $driver_balance_detail->save();
                        $driver_balance_detail = new DriversBalanceDetail();
                        $driver_balance_detail->user_id = Yii::$app->user->getId();
                        $driver_balance_detail->driver_id = $driver_id;
                        $driver_balance_detail->amount = -$order->splitCard;
                        $driver_balance_detail->action = $payment_method."-card";
                        $driver_balance_detail->save();
                    }
                    else {
                        $driver_balance_detail = new DriversBalanceDetail();
                        $driver_balance_detail->user_id = Yii::$app->user->getId();
                        $driver_balance_detail->driver_id = $driver_id;
                        $driver_balance_detail->amount = -$amount;
                        $driver_balance_detail->action = $payment_method." edit ";
                        $driver_balance_detail->save();


                        $driver_balance_detail = new DriversBalanceDetail();
                        $driver_balance_detail->user_id = Yii::$app->user->getId();
                        $driver_balance_detail->driver_id = $driver_id;
                        $driver_balance_detail->amount = $amount;
                        $driver_balance_detail->action = $payment_method;
                        $driver_balance_detail->save();
                    }


                    $logireba = new changeDriver();
                    $logireba->driver_id = $driver_id;
                    $logireba->old_driver_id = $order->driver_id;
                    $logireba->user_id = Yii::$app->user->getId();
                    $logireba->action = "Change cash card";
                    $logireba->order_id = $order->id;
                    $logireba->save();




                }


                $order->payment_method_id = PaymentMethods::getStatusIdByKey($payment_method);
                $order->save();



            }







            /*            $order_action = new OrderActions();
                        $order_action->action = "Edit order for driver";
                        $order_action->order_id = $id;
                        $order_action->user_id = \Yii::$app->user->getId();
                        $order_action->data = $order->order_data;
                        $order_action->save();*/

//
            $order->save();

            $transaction->commit();
            return  "Edited successfully";
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }


    public static function void($id = null,  $pos_id = null) {

        $transaction = \Yii::$app->db->beginTransaction();
        try {

            $order = Orders::find()->where(["id"=>$id])->one();
            $order_data = \yii\helpers\Json::decode($order["order_data"]);

            if (strtolower(OrderStatus::getStatusNameById($order->status)) == "void"
                || strtolower(OrderStatus::getStatusNameById($order->status)) == "refund"
                || strtolower(OrderStatus::getStatusNameById($order->status)) == "finished"
                || strtolower(OrderStatus::getStatusNameById($order->status)) == "waste"
            ){
                return "Coudn't void order in this step";
            }

            if ($order_data["discount"]>0) {
                if ($order_data["discountName"]=="Diplomat") {
                    $price = $order_data["totalPrice"]/1.18;
                } else
                    if ($order_data["discountAmount"])
                        $price = $order_data["totalPrice"]-$order_data["discount"];
                    else
                        $price = $order_data["totalPrice"]-($order_data["totalPrice"]*$order_data["discount"]/100);
            }
            else $price =  $order_data["totalPrice"];

            if(strtolower($order_data["paymentType"]) == "split") {
                $order_data["paymentType"] = "cash";
                $price = intval($order_data["splitCard"])+$order_data["splitCash"];
            }

            if($order->payment_method_id != 4 && $order->payment_method_id != 3)
                $result = PosesAction::EditPosBalanceByPos($pos_id, (-1*$price), "Void", $order_data["paymentType"], $id, $order_data["deliveryType"]);

            $order_action = new OrderActions();
            $order_action->action = "Void";
            $order_action->order_id = $id;
            $order_action->user_id = \Yii::$app->user->getId();
            $order_action->data = $order->order_data;
            $order_action->save();

            $order->user_id = \Yii::$app->user->getId();
            $order->status = OrderStatus::getStatusIdByKey("void");
            $order->save();

            $transaction->commit();
            return  Result::SUCCESS;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function paid($order = null) {

        $transaction = \Yii::$app->db->beginTransaction();
        try {




            $order_data = \Opis\Closure\unserialize($order);

            $order_new = Orders::find()->where(["id"=>$order_data["id"]])->one();

            if ($order_new->payment_method_id != 4) {
                return "Order already Paid";
            }


         if(strtolower($order_data["paymentType"]) == "split") {
                PosesAction::EditPosBalanceByPos($order_data["pos_id"], $order_data["splitCard"], "Paid Split", 'card', $order_data["id"], $order_data["deliveryType"]);
                PosesAction::EditPosBalanceByPos($order_data["pos_id"], $order_data["splitCash"], "Paid Split", "cash", $order_data["id"], $order_data["deliveryType"]);

            } else {



            if ($order_data["discount"]>0) {
                if ($order_data["discountName"]=="Diplomat") {
                    $price = $order_data["totalPrice"]/1.18;
                } else
                    if ($order_data["discountAmount"])
                        $price = $order_data["totalPrice"]-$order_data["discount"];
                    else
                        $price = $order_data["totalPrice"]-($order_data["totalPrice"]*$order_data["discount"]/100);
            }
            else $price =  $order_data["totalPrice"];

            $result = PosesAction::EditPosBalanceByPos($order_data["pos_id"], $price, "Paid", $order_data["paymentType"], $order_data["id"], $order_data["deliveryType"]);

         }

            $order_action = new OrderActions();
            $order_action->action = "Paid";
            $order_action->order_id =  $order_data["id"];
            $order_action->user_id = \Yii::$app->user->getId();
            $order_action->save();


            $order_new->order_data = Json::encode($order_data);
            $order_new->user_id = \Yii::$app->user->getId();
            $order_new->payment_method_id  = PaymentMethods::getStatusIdByKey($order_data["paymentType"]);
            $order_new->status = OrderStatus::getStatusIdByKey("Finished");
            $order_new->save();

            $transaction->commit();
            return  $result;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function discountedOrders($day=null) {

        $day = explode("to", $day);
        $start_day = trim($day[0])." 00:00:00";
        $end_day = trim($day[1]). " 23:59:59";

        $orders = Orders::find()->where([">","created_at", $start_day])->andWhere(["<=","created_at", $end_day])->andWhere(["is_discounted"=>1])
                ->andWhere(["<>","status", OrderStatus::getWaste()])
            ->andWhere(["<>","status", OrderStatus::getRefund()])
            ->andWhere(["<>","status", OrderStatus::getVoid()])
            ->all();
        $result = [];
        foreach ($orders as $row) {
            $row["order_data"] = json_decode($row["order_data"]);
            $result[] = $row;
        }

        return $result;


    }

    public static function invoiceOrders($day=null, $payment_method) {

        $day = explode("to", $day);
        $start_day = trim($day[0])." 00:00:00";
        $end_day = trim($day[1]). "23:59:59";
        $payment_method= PaymentMethods::getStatusIdByKey($payment_method);
        $orders = Orders::find()->where([">","created_at", $start_day])->andWhere(["<","created_at", $end_day])->andWhere(["payment_method_id"=>$payment_method])->all();
        $result = [];
        foreach ($orders as $row) {
            $row["order_data"] = json_decode($row["order_data"]);
            $result[] = $row;
        }

        return $result;


    }


}