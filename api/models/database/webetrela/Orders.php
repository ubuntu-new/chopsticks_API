<?php

namespace api\models\database\webetrela;

use Yii;

/**
 * This is the model class for table "{{%orders}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $duration
 * @property int $status
 * @property int|null $payment_method_id
 * @property int|null $delivery_method_id
 * @property string|null $order_data
 * @property string|null $customer
 * @property string|null $promise_date
 * @property int|null $is_discounted
 * @property string|null $accept_date
 * @property string|null $finish_date
 * @property int|null $driver_id
 * @property string|null $start_delivery
 * @property string|null $end_delivery
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            [["user_id"], 'required'],
//            [['user_id',"customer", 'backer_id', 'pos_id', 'status', 'payment_method_id', 'delivery_method_id', 'reopen', 'is_edited', 'is_discounted', 'driver_id', 'created_at', 'updated_at', 'created_by'], 'integer'],
//            [['order_data'], 'string'],
//            [['finish_date', 'user_id', 'branch', 'status'], 'safe'],
//            [['branch', 'duration', 'source', 'promise_date', 'send_to_backer_date', 'accept_date', 'start_delivery', 'end_delivery'], 'string', 'max' => 255],
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
            'branch' => Yii::t('app', 'Branch'),
            'duration' => Yii::t('app', 'Duration'),
            'backer_id' => Yii::t('app', 'Backer ID'),
            'source' => Yii::t('app', 'Source'),
            'pos_id' => Yii::t('app', 'Pos ID'),
            'status' => Yii::t('app', 'Status'),
            'payment_method_id' => Yii::t('app', 'Payment Method ID'),
            'delivery_method_id' => Yii::t('app', 'Delivery Method ID'),
            'order_data' => Yii::t('app', 'Order Data'),
            'customer' => Yii::t('app', 'Customer'),
            'promise_date' => Yii::t('app', 'Promise Date'),
            'reopen' => Yii::t('app', 'Reopen'),
            'is_edited' => Yii::t('app', 'Is Edited'),
            'is_discounted' => Yii::t('app', 'Is Discounted'),
            'send_to_backer_date' => Yii::t('app', 'Send To Backer Date'),
            'accept_date' => Yii::t('app', 'Accept Date'),
            'finish_date' => Yii::t('app', 'Finish Date'),
            'driver_id' => Yii::t('app', 'Driver ID'),
            'start_delivery' => Yii::t('app', 'Start Delivery'),
            'end_delivery' => Yii::t('app', 'End Delivery'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return OrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrdersQuery(get_called_class());
    }
}
