<?php
namespace rest\modules\v1\controllers;


use api\actions\DriverAction;

use api\actions\OrdersActions;
use api\models\database\DriversBalanceDetail;
use rest\controllers\RestController;
use rest\models\response\Response;


class DriverController extends RestController  {

    public function actionList() {

        $response = new Response();


        $branch = \Yii::$app->request->post("branch");


        if (!$branch) {
            $response->error_message = "Missing parameter: 'Branch'";
            return $response;
        }


        $result = DriverAction::getDriver($branch);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'Operation failed' : '';
        $response->data = $result;
        return $response;
    }

    public function actionSales() {
        $response = new Response();
        $day = \Yii::$app->request->post("day");



        if (!$day) {
            $response->error_message = "Missing parameter: 'Day'";
            return $response;
        }

        $result = DriverAction::getSales($day);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionClockedinDrivers() {

        $response = new Response();


        $branch = \Yii::$app->request->post("branch");


        if (!$branch) {
            $response->error_message = "Missing parameter: 'Branch'";
            return $response;
        }


        $result = DriverAction::getClockedInDriver($branch);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'Operation failed' : '';
        $response->data = $result;
        return $response;
    }

    public function actionUncloseDrivers(){
        $response = new Response();


        $branch = \Yii::$app->request->post("branch");


        if (!$branch) {
            $response->error_message = "Missing parameter: 'Branch'";
            return $response;
        }


        $result = DriverAction::getUncloseDrivers($branch);
        $response->is_error =  count($result)>=0?false:true;
        $response->error_message = count($result)>=0 ? '' : 'Operation failed';
        $response->data = $result;
        return $response;
    }

    public function actionFinishOrder() {
        $driver_id = \Yii::$app->request->post("driver_id");
        $order_id = \Yii::$app->request->post("order_id");
        $payment_method = \Yii::$app->request->post("payment_method");
        $split_card = \Yii::$app->request->post("split_card");
        $split_cash = \Yii::$app->request->post("split_cash");
        $tip = \Yii::$app->request->post("tip")==""?0:\Yii::$app->request->post("tip");



        $response = new Response();
        if (!$driver_id) {
            $response->error_message = "Missing parameter: 'driver_id'";
            return $response;
        }

        $orders =  OrdersActions::finishOrderDelivery($driver_id, $order_id, $payment_method, $split_card, $split_cash, $tip);
        $response->error_message = $orders>0 ?  'Operation failed' : '';
        $response->data = $orders>0?"Something went wrong":"Success";

        return $response;
    }

    public function actionGetBalance() {
        $driver_id = \Yii::$app->request->post("driver_id");
        $day = \Yii::$app->request->post("day");

        $response = new Response();
        if (!$driver_id) {
            $response->error_message = "Missing parameter: '$driver_id'";
            return $response;
        }

        if (!$driver_id) {
            $response->error_message = "Missing parameter: '$driver_id'";
            return $response;
        }


        $orders =  DriverAction::getDriverBalance($driver_id, $day);
        $response->error_message = !$orders ?  'Operation failed' : '';
        $response->data = $orders;

        return $response;
    }

    public function actionEditBalance() {

        $driver_id =  \Yii::$app->request->post('driver_id');
        $amount =  \Yii::$app->request->post('amount');

        $response = new Response();
        if (!$driver_id) {
            $response->error_message = "Missing parameter: 'driver id'";
            return $response;
        }

        if (!$amount) {
            $response->error_message = "Missing parameter: 'Amount'";
            return $response;
        }

        $result = DriverAction::editDriverBalance($driver_id,$amount);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionDetail() {
     //   $driver_id = \Yii::$app->request->post("driver_id");
        $day = \Yii::$app->request->post("day");
        $day = explode("to", $day);

        $response = new Response();
     /*   if (!$driver_id) {
            $response->error_message = "Missing parameter: 'driver_id'";
            return $response;
        }*/
        if (!$day) {
            $response->error_message = "Missing parameter: 'day'";
            return $response;
        }

       /* $result = DriversBalanceDetail::find()->where(["driver_id"=>$driver_id])
            ->andWhere(["LIKE","created_at", $day."%", false])->all();*/

        $result = DriversBalanceDetail::find()->select('drivers_balance_detail.*,user.fullname as name')
            ->leftJoin('user', 'user.id = drivers_balance_detail.driver_id')
         //   ->where(["drivers_balance_detail.driver_id"=>$driver_id])
         ->andWhere([">","drivers_balance_detail.created_at", trim($day[0])." 00:00:00"])
            ->andWhere(["<","drivers_balance_detail.created_at", trim($day[1])." 23:59:00"])

           ->orderBy(["user.id"=>SORT_DESC])
            ->asArray()
            ->all();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionOrdersByDriver() {
        $driver_id = \Yii::$app->request->post("driver_id");
        $day = \Yii::$app->request->post("day");

        $response = new Response();
        if (!$driver_id) {
            $response->error_message = "Missing parameter: 'driver_id'";
            return $response;
        }
        if (!$day) {
            $response->error_message = "Missing parameter: 'day'";
            return $response;
        }

        $result = OrdersActions::ordersByDriver($driver_id, $day);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }



}