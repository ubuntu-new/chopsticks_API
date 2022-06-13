<?php
namespace api\models\database;

use yii\db\ActiveRecord;

/**
 * Entity Status
 *
 * @package api\models
 *
 * @property integer $id
 * @property integer $status
 * @property string $status_key
 * @property string $status_name
 */
class OrderStatus extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() { return "order_statuses"; }
    public static function getStatusIdByKey($key) { return OrderStatus::find()->where(['status_key' => $key])->one()->id; }
    public static function getStatusNameByKey($key) { return OrderStatus::find()->where(['status_key' => $key])->one()->status_name; }
    public static function getStatusNameById($key) { return OrderStatus::find()->where(['id' => $key])->one()->status_name; }

    public static function getPending() { return OrderStatus::getStatusIdByKey('pending'); }
    public static function getInkitchen() { return OrderStatus::getStatusIdByKey('in_kitchen'); }
    public static function getPrepearing() { return OrderStatus::getStatusIdByKey('prepearing'); }
    public static function getFinishedBake() { return OrderStatus::getStatusIdByKey('finished_bake'); }
    public static function getReady() { return OrderStatus::getStatusIdByKey('ready'); }
    public static function getInDelivery() { return OrderStatus::getStatusIdByKey('in_delivery'); }
    public static function getDelivered() { return OrderStatus::getStatusIdByKey('Finished'); }
    public static function getWaste() { return OrderStatus::getStatusIdByKey('waste'); }
    public static function getRefund() { return OrderStatus::getStatusIdByKey('refund'); }
    public static function getVoid() { return OrderStatus::getStatusIdByKey('void'); }
    public static function getReject() { return OrderStatus::getStatusIdByKey('reject'); }
    public static function getDeliveredd() { return OrderStatus::getStatusIdByKey('deliveredd'); }


}