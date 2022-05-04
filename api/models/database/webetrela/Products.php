<?php

namespace api\models\database\webetrela;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property int $w_id
 * @property int $category_id
 * @property string $category_name
 * @property string $name
 * @property string $name_ge
 * @property string $price
 * @property int $weight
 * @property string $class_name
 * @property int $is_special
 * @property int $created_at
 * @property int $status
 * @property string|null $web
 * @property string|null $nutritional
 * @property string|null $description
 * @property string|null $description_ge
 * @property string|null $description_ru
 * @property string|null $is_sticks
 * @property string|null $price_sale
 * @property string|null $gallery
 * @property string|null $is_promo
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'name', 'name_ge', 'name_ru', 'price','status'], 'required'],
            [['w_id', 'category_id', 'weight', 'is_special', 'created_at', 'status'], 'integer'],
            [['w_id','is_promo', 'created_at','description','description_ru','description_ge','class_name', 'category_name','category_id','w_id'], 'safe'],
            [['price', 'web', 'nutritional', 'description','description_ru','description_ge', 'is_sticks', 'gallery', 'is_promo'], 'string'],
            [['category_name', 'name', 'class_name', 'price_sale'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'w_id' => Yii::t('app', 'W ID'),
            'category_id' => Yii::t('app', 'Category ID'),
            'category_name' => Yii::t('app', 'Category Name'),
            'name' => Yii::t('app', 'Name'),
            'name_ge' => Yii::t('app', 'name_ge'),
            'price' => Yii::t('app', 'Price'),
            'weight' => Yii::t('app', 'Weight'),
            'class_name' => Yii::t('app', 'Class Name'),
            'is_special' => Yii::t('app', 'Is Special'),
            'created_at' => Yii::t('app', 'Created At'),
            'status' => Yii::t('app', 'Status'),
            'web' => Yii::t('app', 'Web'),
            'nutritional' => Yii::t('app', 'Nutritional'),
            'description' => Yii::t('app', 'Description'),
            'is_sticks' => Yii::t('app', 'Is Sticks'),
            'price_sale' => Yii::t('app', 'Price Sale'),
            'gallery' => Yii::t('app', 'Gallery'),
            'is_promo' => Yii::t('app', 'Is Promo'),
        ];
    }
}
