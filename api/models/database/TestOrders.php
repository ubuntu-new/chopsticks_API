<?php

namespace api\models\database;


use phpDocumentor\Reflection\Types\String_;
use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $user_id
 * @property int $order_id
 * @property String $data
 * @property String $source
 * @property String $status





 */
class TestOrders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'test_order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['id','user_id','order_id'], 'integer'],
            [['data','source','status'], 'string'],
        ];
    }


}
