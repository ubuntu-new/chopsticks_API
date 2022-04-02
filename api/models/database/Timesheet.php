<?php

namespace api\models\database;

use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property String $state
 * @property int $start_date
 * @property int $end_date





 */
class Timesheet extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'timesheet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['id','user_id','start_date','end_date'], 'integer'],
            [['state'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */

}
