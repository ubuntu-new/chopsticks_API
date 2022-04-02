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
 * @property int $branch_id
 * @property string $branch_name
 * @property int $pos_id
 * @property double $price
 * @property \DateTime $created_at




 */
class Poses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'poses';
    }
}
