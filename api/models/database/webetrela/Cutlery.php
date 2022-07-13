<?php

namespace api\models\database\webetrela;

use Yii;

/**
 * This is the model class for table "contactinfo".
 *
 * @property int $id
 * @property string $name
 * @property string|null $weight
 * @property string|null $name_ge
 * @property string|null $name_ru
 * @property string $ismain
 * @property string $price
 */
class Cutlery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cutlery';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','name_ge','name_ru',], 'required'],
            [['weight', 'ismain'], 'safe'],
            [['name', 'name_ge','name_ru'], 'string']
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
            'name_ge' => Yii::t('app', 'Name GE'),
            'name_ru' => Yii::t('app', 'Name RU'),
        ];
    }
}
