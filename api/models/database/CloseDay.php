<?php

namespace api\models\database;


use app\models\Pcategory;
use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "PosesBalanceDetail".
 *
 * @property int $id
 * @property int $pos_id
 * @property int $driver_id
 * @property int $user_id
 * @property double $difference
 * @property double $diff_card
 * @property double $diff_cash
 * @property string $comment
 * @property \DateTime $created_at




 */
class CloseDay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'close_day';
    }
}
