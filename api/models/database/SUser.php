<?php

namespace api\models\database;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $fullname
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $pin
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $verification_token
 * @property string $access_token
 * @property string $branch
 * @property int $branch_id
 */
class SUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username','email','password_hash'], 'required'],
            [['status', 'created_at', 'updated_at', 'branch_id'], 'integer'],
            [['fullname', 'username', 'password_hash', 'pin', 'password_reset_token', 'email', 'verification_token', 'access_token', 'branch'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['auth_key', 'pin', 'created_at', 'updated_at', 'access_token', 'branch', 'branch_id', 'auth_key' ], 'safe'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'trim'],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['email', 'trim'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'fullname' => Yii::t('app', 'Fullname'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'pin' => Yii::t('app', 'Pin'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'verification_token' => Yii::t('app', 'Verification Token'),
            'access_token' => Yii::t('app', 'Access Token'),
            'branch' => Yii::t('app', 'Branch'),
            'branch_id' => Yii::t('app', 'Branch ID'),
        ];
    }
}
