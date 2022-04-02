<?php

namespace api\models\database;

use phpDocumentor\Reflection\Types\String_;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $address
 * @property string $tel
 * @property string $comment
 * @property string $comment2
 * @property string $b_day
 * @property string $discount
 * @property string $ltd_id
 * @property string $ltd_name
 * @property string $personal_id
 * @property string $email
 * @property string $gender
 * @property string $door_code
 * @property string $floor
 * @property string $flat_number
 * @property string $city
 * @property string $country
 * @property int $order_count
 * @property int $loyal
 * @property \DateTime $created_at
 * @property \DateTime $updated_at


 */
class CustomersW extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers_max_w';
    }

    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }
}
