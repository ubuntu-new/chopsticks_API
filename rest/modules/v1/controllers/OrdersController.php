<?php
namespace rest\modules\v1\controllers;
use api\actions\OrdersActions;
use api\actions\UserAction;
use api\models\database\OrderActions;
use rest\controllers\LangController;
use rest\controllers\RestController;
use rest\models\response\Response;
use yii\helpers\Json;

class OrdersController extends LangController  {

    public function actionList() {
        $response = new Response();
        $user_id = \Yii::$app->request->post("user_id");

        if (!$user_id) {
            $response->error_message = "Missing parameter: 'user_id'";
            return $response;
        }

        $result = OrdersActions::OrdersList($user_id);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;

    }


    public function actionCreate() {

        $items =  (\Yii::$app->request->post('items'))?\Yii::$app->request->post('items'):[];
        $customer = (\Yii::$app->request->post('customer'))?\Yii::$app->request->post('customer'):[];
        $user_id = (\Yii::$app->request->post('user_id'))?\Yii::$app->request->post('user_id'):[];


            $result = OrdersActions::OrdersCreate(Json::encode($items), Json::encode($customer), $user_id);

        return $result;
    }








}