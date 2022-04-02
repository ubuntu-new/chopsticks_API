<?php

namespace api\models\database;


use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "drivers_balance".
 *
 * @property int $id
 * @property int $user_id
 * @property int $driver_id
 * @property float $amount
 * @property float $tip
 * @property string $action
 * @property string $created_at
 */

class DriversBalanceDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'drivers_balance_detail';
    }

    /**
     * {@inheritdoc}
     */



}
