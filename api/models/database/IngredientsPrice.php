<?php

namespace api\models\database;


use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "Ingredients".
 *
 * @property int $id
 * @property String $s
 * @property String $m
 * @property String $xl
 * @property int $is_premium
 */

class IngredientsPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredient_prices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['id','is_premium'], 'integer'],
            [['s','m','xl'], 'string'],
        ];
    }


}
