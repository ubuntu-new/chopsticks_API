<?php

namespace api\models\database;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "recipes".
 *
 * @property int $id
 * @property int $product_id
 * @property int $child_product_id
 * @property string|null $parent
 * @property string|null $child
 * @property string $unit
 * @property string $qty
 * @property string $small
 * @property string $large
 * @property string $recipe_result_min
 * @property string $recipes_result_max
 * @property \TimeStamp $created
 * @property double $visible
 */
class Recipes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recipes';
    }
    public static function getDb() {
        return \Yii::$app->dbw;
    }
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {

        return [
            [['parent', 'child', 'unit', 'qty','small','large','recipe_result_min','recipes_result_max','visible'], 'string'],
//            [['product_id'], 'required'],
            [['created','unit', 'qty','small','large','recipe_result_min','recipes_result_max','product_id','child_product_id'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product id',
            'child_product_id' => 'Child Product Id',
            'parent' => 'Parent',
            'child' => 'Child',
            'unit' => 'Unit',
            'qty' => 'Qty',
            'small' => 'Small',
            'large' => 'Large',
            'recipe_result_min' => 'Recipe Result Min Value',
            'recipes_result_max' => 'Recipe Result Max Value',
            'visible' => 'Visible',
        ];
    }


}
