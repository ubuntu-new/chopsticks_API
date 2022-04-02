<?php
namespace api\actions;


use api\models\response\OrdersSiteResponse;
use api\models\database\Orders;
use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;
use Yii;



class OrdersSiteAction {
    public static function getList($user_id = null){

        $result = [];
        $sql = "SELECT * FROM orders WHERE user_id = $user_id limit 3";
        $orders_site = \Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);

        foreach ($orders_site as $row) {
            $result[] = new OrdersSiteResponse($row);
        }
        return $result;
    }

}