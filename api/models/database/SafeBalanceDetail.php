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
 * @property double $amount
 * @property string $payment
 * @property string $comment
 * @property int $bank_id
 * @property int $bank_name
 * @property \DateTime $created_at
 * @property int $safe_id




 */
class SafeBalanceDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'safe_balance_detail';
    }
}
