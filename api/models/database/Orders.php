<?php

namespace api\models\database;

use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property int $backer_id
 * @property string $source
 * @property int $pos_id
 * @property String $branch
 * @property String $promise_date
 * @property String $opay_status
 * @property String $opay_order_id
 * @property String $shop_order_id
 * @property int $order_data
 * @property int $payment_method_id
 * @property int $delivery_method_id
 * @property int $status
 * @property boolean $is_discounted
 * @property String $duration
 * @property \DateTime $send_to_backer_date
 * @property \DateTime $accept_date
 * @property \DateTime $finish_date
 * @property \DateTime $created_at




 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */

}
