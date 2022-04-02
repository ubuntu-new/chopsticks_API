<?php

namespace api\models\database;


use app\models\Pcategory;
use phpDocumentor\Reflection\Types\String_;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $branch_id
 * @property string $branch
 * @property double $amount
 * @property \DateTime $created_at




 */
class Safe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'safe';
    }


}
