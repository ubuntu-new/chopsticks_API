<?php

namespace api\models\database;


use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "Ingredients".
 *
 * @property int $id
 * @property String $name
 * @property int $weight
 * @property String $status
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
            [['name','status'], 'string'],
        ];
    }


}
