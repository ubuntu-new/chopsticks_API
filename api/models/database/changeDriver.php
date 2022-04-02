<?php

namespace api\models\database;


use app\models\Pcategory;
use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "PosesBalanceDetail".
 *
 * @property int $id
 * @property string $driver_id
 * @property string $old_driver_id
 * @property string $order_id
 * @property string $user_id
 * @property string $action
 * @property \DateTime $created_at




 */
class changeDriver extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logireba';
    }
}
