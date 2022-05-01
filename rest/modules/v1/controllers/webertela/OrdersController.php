<?php
/**
 * Created by PhpStorm.
 * User: levan
 * Date: 04/17/22
 * Time: 15:36
 */

namespace rest\modules\v1\controllers\webertela;

use api\models\database\webetrela\Orders;
use api\models\response\Result;
use yii\rest\ActiveController;

class OrdersController extends ActiveController
{
    public $modelClass = Orders::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

    public function actionCreate(){
        $user_id = \Yii::$app->request->post("user_id");
        $response = new Response();
        if(!$user_id) {
            return $this->errorResponse("missing parameter user_id",400);
            return $response;
        }


        $post = \Yii::$app->request->post();
        $order_data = $post["product_list"];
        $customer = $post["customer"];

        return $order_data;

//        $order_data = \Opis\Closure\unserialize($data);


//        $order_id =  $order_data["orderId"];


        return Result::FAILURE;
}
}

