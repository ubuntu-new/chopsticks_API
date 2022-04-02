<?php

namespace app\models;


use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property int $backer_id
 * @property int $source
 * @property String $branch
 * @property int $order_data
 * @property int $status
 * @property String $duration
 * @property \DateTime $send_to_backer_date
 * @property \DateTime $accept_date
 * @property \DateTime $finish_date
 * @property \DateTime $created_at




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

            [['id','order_id','user_id','backer_id','status'], 'integer'],
            [['branch','send_to_backer_date','accept_date','source','finish_date','order_data'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'order_id' => Yii::t('app', 'Title'),
            'user_id' => Yii::t('app', 'user_id'),
            'status' => Yii::t('app', 'status'),
            'send_to_backer_date' => Yii::t('app', 'send_to_backer_date'),
            'accept_date' => Yii::t('app', 'accept_date'),
            'finish_date' => Yii::t('app', 'finish_date'),
            'created_at' => Yii::t('app', 'created_at'),
        ];
    }
}
