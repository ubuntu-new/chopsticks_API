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
 * @property string $product_unit
 * @property integer $from_warehouse_id
 * @property string $from_warehouse_name
 * @property integer $to_warehouse_id
 * @property string $to_warehouse_name
 * @property double $sent_quantity
 * @property double $recieved_quantity
 * @property integer $request_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $comment








 */
class WTicket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
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
