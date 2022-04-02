<?php
namespace api\models\database;

use yii\db\ActiveRecord;

/**
 * Entity Status
 *
 * @package api\models
 *
 * @property integer $id
 * @property string $status_key
 * @property string $status_name
 */
class PaymentMethods extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName() { return "payment_methods"; }

    public static function getStatusIdByKey($key) { return PaymentMethods::find()->where(['status_key' => $key])->one()->id; }

    public static function getCard() { return PaymentMethods::getStatusIdByKey('Card'); }

    public static function getSplit() { return PaymentMethods::getStatusIdByKey('split'); }

    public static function getCash() { return PaymentMethods::getStatusIdByKey('Cash'); }

    public static function getInvoice() { return PaymentMethods::getStatusIdByKey('Invoice'); }

    public static function getPayLayter() { return PaymentMethods::getStatusIdByKey('payLater'); }



}