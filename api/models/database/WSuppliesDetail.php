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
 * @property int $user_id
 * @property int $supplie_id
 * @property int $product_id
 * @property int $request_id
 * @property string $action
 * @property string $product_name
 * @property integer $quantity
 * @property integer $from_warehouse_id
 * @property string $from_warehouse_name
 * @property integer $to_warehouse_id
 * @property string $to_warehouse_name
 * @property integer $created_at
 * @property integer $updated_at






 */
class WSuppliesDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'supplies_detail';
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
