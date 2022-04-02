<?php

namespace app\models;


use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "Ingredients".
 *
 * @property int $id
 * @property String $name
 * @property int $weight
 */

class Ingredients extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ingredients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['id','weight'], 'integer'],
            [['name'], 'string'],
        ];
    }


}
