<?php
namespace rest\modules\v1\controllers;

//use api\actions\CustomersAction;
use api\actions\Site_customersAction;
use api\models\database\Customers;
use api\models\response\OrdersResponse;
use rest\controllers\RestController;
use rest\models\response\Response;
use yii\helpers\Json;

class Site_custommersController extends RestController  {

    public function actionCreateCustomer() {

        $response = new Response();

        $id =  \Yii::$app->request->post('id');
        $phone =  \Yii::$app->request->post('phone');
        $address = \Yii::$app->request->post("address");
        $comment = \Yii::$app->request->post("comment");
        $comment2 = \Yii::$app->request->post("comment2");
        $name = \Yii::$app->request->post("name");
        $b_day = \Yii::$app->request->post("b_day");
        $discount = \Yii::$app->request->post("discount");
        $email = \Yii::$app->request->post("email");
        $gender = \Yii::$app->request->post("gender");
        $ltd_id = \Yii::$app->request->post("ltd_id")?\Yii::$app->request->post("ltd_id"):null;
        $ltd_name = \Yii::$app->request->post("ltd_name")?\Yii::$app->request->post("ltd_name"):null;
        $personal_id = \Yii::$app->request->post("personal_id");


        if (!$name) {
            $response->error_message = "Missing parameter: 'name'";
            return $response;
        }

        if (!$phone) {
            $response->error_message = "Missing parameter: 'phone'";
            return $response;
        }

//        $customer = CustomersAction::findCustomer($phone);
//        if ($customer) {
//            $response->error_message = 'Phone number exists';
//            return $response;
//        }
        $result = CustomersAction::createCustomer($id, $name, \Opis\Closure\serialize($address), \Opis\Closure\serialize($phone), $comment, $comment2, $b_day, $gender, $email, $discount, $personal_id, $ltd_id, $ltd_name);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'Operation failed' : '';
        $response->data = $result;
        return $response;
    }



    public function actionGetCustomer() {
        $phone =  \Yii::$app->request->post('phone');
        $response = new Response();
        if (!$phone) {
            $response->error_message = "Missing parameter: 'phone'";
            return $response;
        }

        $result = CustomersAction::findCustomerJson($phone);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }


    public function actionLastOrder() {
        $phone =  \Yii::$app->request->post('phone');
        $response = new Response();
        if (!$phone) {
            $response->error_message = "Missing parameter: 'phone'";
            return $response;
        }

        $result = CustomersAction::getLastOrder($phone);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }


    public function  actionGetAllOrdersByPhone() {
        $phone =  \Yii::$app->request->post('phone');
        $response = new Response();
        if (!$phone) {
            $response->error_message = "Missing parameter: 'Tel numbers'";
            return $response;
        }
        $result = CustomersAction::getAllOrders($phone);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $res = [];
        foreach ($result as $order) {
            $res[] = new OrdersResponse($order);
        }
        $response->data = $res;
        return $response;
    }



}