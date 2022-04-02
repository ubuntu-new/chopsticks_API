<?php
namespace api\models\database;

use Yii;
use yii\base\Event;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $id_num
 * @property string $plain_pass
 *
 * @property string $access_token
 * @property integer $last_login
 * @property string $default_lang
 * @property integer $filter_country
 */
class SUser extends ActiveRecord  {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'safe'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

        ];
    }


    /**
     * @inheritdoc
     */
    public function getSex()
    {
        return (new Query())->select('sex')->from('user_cv')
            ->where(['user_id' => $this->id, 'is_default' => true])
            ->limit(1)->scalar();
    }
}