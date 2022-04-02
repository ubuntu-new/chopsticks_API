<?php

namespace api\models\database;

use phpDocumentor\Reflection\Types\String_;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $customers_id	int(
 * @property int $coupons_id
 * @property \DateTime $created_at
 * @property \DateTime $updated_at



 */
class CustomerToCoupons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customers_to_coupons';
    }

    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }
}
