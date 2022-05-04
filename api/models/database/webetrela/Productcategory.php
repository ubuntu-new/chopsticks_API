<?php

namespace api\models\database\webetrela;

use Yii;

/**
 * This is the model class for table "{{%product_category}}".
 *
 * @property int $id
 * @property string $name
 * @property int $w_id
 * @property int $weight
 * @property string $status
 * @property string|null $name_ge
 * @property string|null $name_ru
 * @property string|null $url
 */
class Productcategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name',  'url'], 'required'],
            [['w_id', 'weight'], 'integer'],
            [['w_id'], 'safe'],
            [['status'], 'string'],
            [['name', 'name_ge', 'name_ru'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'w_id' => Yii::t('app', 'W ID'),
            'weight' => Yii::t('app', 'Weight - '),
            'status' => Yii::t('app', 'Status must be 0'),
            'name_ge' => Yii::t('app', 'Name Ge'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'url' => Yii::t('app', 'url'),
        ];
    }
}
