<?php
/**
 * Created by PhpStorm.
 * User: levan
 * Date: 4/7/2020
 * Time: 21:26
 */

namespace frontend\controllers;
use api\actions\OrdersActions;
use app\models\Orders;
use yii\helpers\Json;
use yii\web\Controller;
use app\models\Post;


class PostController extends Controller
{
    public function actionIndex()
    {

       return $this->render('index');

    }

    public function actionGetOrders()
    {
        $branch = \Yii::$app->request->post("branch");
        $status = \Yii::$app->request->post("status");

        return OrdersActions::getOrdersBacker($branch, $status);

    }
    public function actionGetOrdersByDate()
    {
        $branch = \Yii::$app->request->post("branch");
        $status = \Yii::$app->request->post("status");
        $date = \Yii::$app->request->post("date");

        return OrdersActions::getOrdersBacker($branch, $status, $date);

    }
    public function actionUpdateOrders()
    {

        $order_id= \Yii::$app->request->post("order_id");
        $status= \Yii::$app->request->post("status");

        return OrdersActions::updateOrder($order_id, $status);

    }
}