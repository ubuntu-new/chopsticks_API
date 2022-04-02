<?php
namespace api\models\database;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * address book
 *
 * @package api\models
 *
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
class AddressBookTheme extends ActiveRecord {

    public static function tableName() { return 'orders';  }

    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }

}