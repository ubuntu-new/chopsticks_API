<?php

namespace api\models\database\webetrela;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $duration
 * @property int|null $status
 * @property int|null $payment_method_id
 * @property int|null $delivery_method_id
 * @property string|null $order_data
 * @property string|null $promise_date
 * @property int|null $is_discounted
 * @property string|null $accept_date
 * @property string|null $finish_date
 * @property int|null $driver_id
 * @property string|null $start_delivery
 * @property string|null $end_delivery
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $created_by
 * @property string|null $driver_name
 * @property string|null $customer
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'status', 'payment_method_id', 'delivery_method_id', 'is_discounted', 'driver_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
            [['order_data', 'customer'], 'string'],
            [['finish_date','driver_name'], 'safe'],
            [['duration', 'promise_date', 'accept_date', 'start_delivery', 'end_delivery'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'duration' => Yii::t('app', 'Duration'),
            'status' => Yii::t('app', 'Order Status'),
            'payment_method_id' => Yii::t('app', 'Payment Method ID'),
            'delivery_method_id' => Yii::t('app', 'Delivery Method ID'),
            'order_data' => Yii::t('app', 'Order Data'),
            'promise_date' => Yii::t('app', 'Promise Date'),
            'is_discounted' => Yii::t('app', 'Is Discounted'),
            'accept_date' => Yii::t('app', 'Accept Date'),
            'finish_date' => Yii::t('app', 'Finish Date'),
            'driver_id' => Yii::t('app', 'Driver ID'),
            'start_delivery' => Yii::t('app', 'Start Delivery'),
            'end_delivery' => Yii::t('app', 'End Delivery'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
            'customer' => Yii::t('app', 'Customer'),
            'driver_name' => Yii::t('app', 'Driver fullnamename'),
        ];
    }
}
