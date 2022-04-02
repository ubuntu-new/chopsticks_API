<?php

namespace api\models\database;

use phpDocumentor\Reflection\Types\String_;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "post".
 *
 * @property intint $id
 * @property intint $branches_id
 * @property string $working_days
 * @property int $status


 */
class Branches_working_hours extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'branches_working_hours';
    }

}
