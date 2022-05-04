<?php

namespace api\models\database;


use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "Ingredients".
 *
 * @property int $id
 * @property string $name
 * @property string $name_ge
 * @property string $name_ru
 * @property string $url
 * @property int $iw_id
 * @property int $weight
 */

class Product_categories extends \yii\db\ActiveRecord
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



}
