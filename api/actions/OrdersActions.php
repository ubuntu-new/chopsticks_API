<?php
/**
 * Created by PhpStorm.
 * User: levan
 * Date: 4/13/2020
 * Time: 12:42
 */

namespace api\actions;


use api\models\database\DriversBalanceDetail;
use api\models\database\OrderActions;
use api\models\database\PaymentMethods;
use api\models\database\Status;
use api\models\response\Result;
use api\models\database\webetrela\Orders;
use Yii;
use yii\db\Exception;
use mdm\admin\models\User;
use yii\helpers\Json;

class OrdersActions
{
    public static function OrdersList($user_id = null) {

        $sql = Orders::find()->where(["status"=>1])->andWhere(["user_id"=>$user_id])->all();

       return $sql;
        $result = [];
             foreach ($sql as $row) {
                 $row["order_data"] = json_decode($row["order_data"]);
                 $result[] = $row;
             }
        return $result;

    }

    public static function OrdersCreate($order_data =null, $customer =null, $cutlery =null, $lang =null, $user_id =null, $tottalprice =null, $payment_method = null) {
        if($payment_method == 'cash-on-delivery')
            $payment_method = "Cash";
        $transaction = \Yii::$app->db->beginTransaction();
        try {
                $order = new Orders();
                $order->order_data	 = $order_data;
                $order->customer	 = $customer;
                $order->cutlery	 = $cutlery;
                $order->payment_method_id = PaymentMethods::getStatusIdByKey($payment_method);
                $order->lang	 = $lang;
                $order->status = Status::getActive();
//                $order->created_at =date("Y-m-d h:i:s");
                $order->user_id = $user_id;
                $order->tottalPrice = $tottalprice;
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

}