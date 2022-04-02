<?php

namespace app\models;


use phpDocumentor\Reflection\Types\String_;
use Yii;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property int $order_id
 * @property int $user_id
 * @property int $backer_id
 * @property String $branch
 * @property int $status
 * @property \DateTime $send_to_backer_date
 * @property \DateTime $accept_date
 * @property \DateTime $finish_date
 * @property \DateTime $created_at




 */
class Orders_db extends ActiveRecord {

    public static function tableName() { return 'orders';  }

    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }

}
