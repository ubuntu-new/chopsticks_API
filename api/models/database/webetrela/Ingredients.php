<?php

namespace api\models\database\webetrela;

use Yii;

/**
 * This is the model class for table "{{%ingredients}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $name_ge
 * @property string|null $name_ru
 * @property int $weight
 * @property int $isPremium
 * @property int $base
 * @property string|null $class_name
 * @property int $product_category_id
 * @property string|null $status
 * @property string|null $price
 */
class Ingredients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%ingredients}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name','name_ge', 'name_ru'], 'required'],
            [['weight', 'isPremium', 'base', 'product_category_id'], 'integer'],
            [['status'], 'string'],
            [['name', 'name_ge', 'name_ru', 'class_name', 'price'], 'string', 'max' => 255],
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
            'name_ge' => Yii::t('app', 'Name Ge'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'weight' => Yii::t('app', 'Weight'),
            'isPremium' => Yii::t('app', 'Is Premium'),
            'base' => Yii::t('app', 'Base'),
            'class_name' => Yii::t('app', 'Class Name'),
            'product_category_id' => Yii::t('app', 'Product Category ID'),
            'status' => Yii::t('app', 'Status'),
            'price' => Yii::t('app', 'Price'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return IngredientsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new IngredientsQuery(get_called_class());
    }
}
