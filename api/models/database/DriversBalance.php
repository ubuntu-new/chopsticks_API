<?php

namespace api\models\database;


use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "drivers_balance".
 *
 * @property int $id
 * @property int $driver_id
 * @property float $amount
 * @property float $card
 * @property float $tip
 * @property string $created_at
 * @property string $start_time
 * @property string $end_time
 */

class DriversBalance extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'drivers_balance';
    }

    /**
     * {@inheritdoc}
     */



}
