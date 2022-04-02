<?php

namespace api\models\database;


use app\models\Pcategory;
use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property String $name
 * @property String $category_name
 * @property int $w_id
 * @property double $price
 * @property int $category_id
 * @property \DateTime $created_at
 * @property \double $web
 * @property \String $nutritional
 * @property \String $description




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

            [['id','w_id','category_id'], 'integer'],
            [['name','price','category_name'], 'string'],
            [['web','nutritional','description'], 'safe'],
        ];
    }

    public function getProductCategory()
    {
        return $this->hasOne(\api\models\database\Pcategory::class, ['id' => 'category_id']);
    }


}
