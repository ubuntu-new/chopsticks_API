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
 * @property int $discount
 * @property string $tel
 * @property string $comment
 * @property int $status
 * @property \DateTime $created_at
 * @property \DateTime $updated_at



 */
class Coupons extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coupons';
    }

    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }
}
