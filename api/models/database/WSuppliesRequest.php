<?php

namespace api\models\database;


use app\models\Pcategory;
use phpDocumentor\Reflection\Types\String_;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "PosesBalanceDetail".
 *
 * @property int $id
 * @property int $product_id
 * @property string $product_name
 * @property integer $from_warehouse_id
 * @property string $from_warehouse_name
 * @property integer $to_warehouse_id
 * @property string $to_warehouse_name
 * @property int $user_id
 * @property string $unit
 * @property integer $quantity
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $main_w_action_date
 * @property string $w_action_date
 * @property string $sent_quantity
 * @property string $recieve_quantity






 */
class WSuppliesRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'request_supplies';
    }
    public static function getDb() {
        return \Yii::$app->dbw;
    }

    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
}
