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
class DeliveryMethods extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() { return "delivery_methods"; }

    public static function getStatusIdByKey($key) { return DeliveryMethods::find()->where(['status_key' => $key])->one()->id; }

    public static function getWolt() { return DeliveryMethods::getStatusIdByKey('Wolt'); }

    public static function getRonny() { return DeliveryMethods::getStatusIdByKey('Ronnys'); }

    public static function getGlovo() { return DeliveryMethods::getStatusIdByKey('Glovo'); }

    public static function getWalkIn() { return DeliveryMethods::getStatusIdByKey('Walk_in'); }

    public static function getTakeOut() { return DeliveryMethods::getStatusIdByKey('Take_out'); }

}