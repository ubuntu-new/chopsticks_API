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
 * @property int $order_id
 * @property double $amount
 * @property string $action
 * @property string $payment_method
 * @property int $user_id
 * @property \DateTime $created_at




 */
class PosesBalanceDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'poses_balance_detail';
    }
}
