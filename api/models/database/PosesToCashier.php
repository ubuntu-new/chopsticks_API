<?php

namespace api\models\database;

use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $pos_id
 * @property int $user_id
 * @property \DateTime $created_at




 */
class PosesToCashier extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'poses_to_cashier';
    }

    /**
     * {@inheritdoc}
     */


}
