<?php

namespace api\models\database;


use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "Ingredients".
 *
 * @property int $id
 * @property int $product_id
 * @property int $ingredients_id
 * @property string $default_weight
 * @property string $topping_wight
 */

class Receipt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipt';
    }

    /**
     * {@inheritdoc}
     */



}
