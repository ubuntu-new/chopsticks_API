<?php

namespace app\models;


use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property String $name
 * @property int $w_id
 * @property \DateTime $created_at




 */
class Pcategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['id','w_id'], 'integer'],
            [['name'], 'string'],
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
