<?php
namespace rest\modules\v1\controllers;


use rest\controllers\RestController;
use yii\helpers\Json;

class TestController extends RestController
{
    public function actionTest() {
        $order_id = 4274;
        $orders = \api\models\database\Orders::find()->where(["id"=>$order_id])->one();
        $or = Json::decode($orders->order_data);
        return $or;
    }
}