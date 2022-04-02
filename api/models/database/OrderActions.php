<?php

namespace api\models\database;

use phpDocumentor\Reflection\Types\String_;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $action
 * @property string $data
 * @property int $order_id
 * @property int $user_id
 * @property string $created_at


 */
class OrderActions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders_action';
    }

}
