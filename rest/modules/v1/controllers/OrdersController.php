<?php
namespace rest\modules\v1\controllers;
use api\actions\OrdersActions;
use api\actions\UserAction;
use api\models\database\OrderActions;
use api\actions\SmsActions;
use rest\controllers\LangController;
use rest\controllers\RestController;
use rest\models\response\Response;
use yii\helpers\Json;

class OrdersController extends LangController  {

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
            ],
        ];
    }
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
        $lang = (\Yii::$app->request->post('lang'))?\Yii::$app->request->post('lang'):[];
        $cutlery = (\Yii::$app->request->post('cutlery'))?\Yii::$app->request->post('cutlery'):[];
        $user_id = (\Yii::$app->request->post('user_id'))?\Yii::$app->request->post('user_id'):[];
        $tottalprice = (\Yii::$app->request->post('tottalPrice'))?\Yii::$app->request->post('tottalPrice'):[];

            $result = OrdersActions::OrdersCreate(Json::encode($items), Json::encode($customer), Json::encode($cutlery), $lang, $user_id, $tottalprice);


        return $result;
    }

    public function actionSms() {
        return SmsActions::sendSms('599320100', 'BLA');
    }








}