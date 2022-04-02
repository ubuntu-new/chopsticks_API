<?php
namespace rest\modules\v1\controllers;
use api\actions\DriverAction;
use api\actions\OrdersActions;
use api\actions\PosesAction;
use api\actions\TimesheetAction;
use api\actions\UserAction;
use api\models\database\User;
use rest\controllers\RestController;
use rest\models\response\Response;
use Yii;

class ManagerController extends RestController  {

    public function actionGetNewOrders() {
        $branch = \Yii::$app->request->post("branch") ? \Yii::$app->request->post("branch") : null;
        $response = new Response();

        $orders =  OrdersActions::getGetOrdersNewForPos($branch);
        $response->is_error = !$response;
        $response->error_message = !$response ?  'Operation failed' : '';
        $response->data = $orders;
        return $response;
    }

    public function actionGetDeliveryOrders() {
        $branch = \Yii::$app->request->post("branch");
           $response = new Response();
        if (!$branch) {
            $response->error_message = "Missing parameter: 'Branch'";
            return $response;
        }

        $orders =  OrdersActions::getGetDeliveryOrders($branch);
        $response->is_error = !$response;
        $response->error_message = !$response ?  'Operation failed' : '';
        $response->data = $orders;
        return $response;
    }

    public function actionGetCurrentOrders()
    {
        $branch = \Yii::$app->request->post("branch");
        $status = \Yii::$app->request->post("status");
        $created = \Yii::$app->request->post("created_at") ? \Yii::$app->request->post("created_at"): null;

        $response = new Response();
        if (!$branch) {
            $response->error_message = "Missing parameter: 'Branch'";
            return $response;
        }
        if (!$status) {
            $response->error_message = "Missing parameter: 'Status'";
            return $response;
        }

        $orders =  OrdersActions::getCurrentOrdersForPos($branch, $status, $created);
        $response->error_message = !$orders ?  'Operation failed' : '';
        $response->data = $orders;

        return $response;

    }

    public function actionAttachOrderToDriver() {
        $order_id = \Yii::$app->request->post("order_id");
        $driver_id = \Yii::$app->request->post("driver_id");


        $response = new Response();
        if (!$order_id) {
            $response->error_message = "Missing parameter: 'order_id'";
            return $response;
        }
        if (!$driver_id) {
            $response->error_message = "Missing parameter: 'Driver_id'";
            return $response;
        }

        $orders =  OrdersActions::attachOrderToDriver($order_id, $driver_id);
        $response->is_error = !$orders;
        $response->error_message = !$orders ?  'Operation failed' : '';
        $response->data = $orders;

        return $response;
    }

    public function actionRemoveOrderFromDriver() {
        $order_id = \Yii::$app->request->post("order_id");


        $response = new Response();
        if (!$order_id) {
            $response->error_message = "Missing parameter: 'order_id'";
            return $response;
        }


        $orders =  OrdersActions::removeOrderFromDriver($order_id);
        $response->is_error = !$orders;
        $response->error_message = !$orders ?  'Operation failed' : '';
        $response->data = $orders;

        return $response;
    }

    public function actionAddBalanceToDriver() {
        $driver_id = \Yii::$app->request->post("driver_id");
        $amount = \Yii::$app->request->post("amount");

        $response = new Response();
        if (!$driver_id) {
            $response->error_message = "Missing parameter: 'driver_id'";
            return $response;
        }
        if (!$amount) {
            $response->error_message = "Missing parameter: 'amount'";
            return $response;
        }

        $orders =  DriverAction::AddbalanceToDriver($driver_id, $amount);
        $response->error_message = !$orders ?  'Operation failed' : '';
        $response->data = $orders;

        return $response;
    }

    public function actionUpdateOrderStatus()
    {
        $duration =  Yii::$app->request->post("duration");
        return OrdersActions::updateOrderStatus(Yii::$app->request->post("order_id"), Yii::$app->request->post("status"), $duration);
    }

    public function actionCancelOrder()
    {

        return OrdersActions::cancelOrder(Yii::$app->request->post("order_id"), Yii::$app->request->post("text"), Yii::$app->request->post("user"), Yii::$app->request->post("mail"));
    }

    public function actionChangeOrderAddress()
    {

        return OrdersActions::changeOrderAddress(Yii::$app->request->post("order_id"), Yii::$app->request->post("method_id"), Yii::$app->request->post("address"));
    }

    public function actionClockInUsers() {
        $day = \Yii::$app->request->post("day");

        $response = new Response();
        if (!$day) {
            $response->error_message = "Missing parameter: 'day'";
            return $response;
        }

        $result = UserAction::clockedInUsers($day);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionCloseDay() {
        $pos_id = \Yii::$app->request->post("pos_id");
        $driver_id = \Yii::$app->request->post("driver_id");
        $diff_card = \Yii::$app->request->post("diff_card");
        $diff_cash = \Yii::$app->request->post("diff_cash");
        $comment = \Yii::$app->request->post("comment");

        $response = new Response();
        if ($pos_id<0) {
            $response->error_message = "Missing parameter: 'pos_id'";
            return $response;
        }



        $result = PosesAction::closeDay($pos_id, $driver_id, $diff_card, $diff_cash, $comment);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionGetCloseDay() {
        $day = \Yii::$app->request->post("day");

        $response = new Response();

        if (!$day) {
            $response->error_message = "Missing parameter: 'day'";
            return $response;
        }

        $result = PosesAction::getCloseDay($day);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionDetailTimesheet() {
        $day = \Yii::$app->request->post("day");
        $user_id = \Yii::$app->request->post("user_id");

        $response = new Response();
        if (!$day) {
            $response->error_message = "Missing parameter: 'day'";
            return $response;
        }
        if (!$user_id) {
            $response->error_message = "Missing parameter: 'user_id'";
            return $response;
        }

        $result = TimesheetAction::detailTimesheet($day, $user_id);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionDiscountedOrders() {
        $day = \Yii::$app->request->post("day");
        $response = new Response();
        if (!$day) {
            $response->error_message = "Missing parameter: 'day'";
            return $response;
        }


        $result = OrdersActions::discountedOrders($day);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionOrdersByPayment() {
        $day = \Yii::$app->request->post("day");
        $payment_method = \Yii::$app->request->post("payment_method");

        $response = new Response();
        if (!$day) {
            $response->error_message = "Missing parameter: 'day'";
            return $response;
        }


        $result = OrdersActions::invoiceOrders($day, $payment_method);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionCheckPin(){
        $pin = \Yii::$app->request->post("pin");
        $response = new Response();
        if (!$pin) {
            $response->error_message = "Missing parameter: 'pin'";
            return $response;
        }

        $result = PosesAction::checkPin($pin);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

}