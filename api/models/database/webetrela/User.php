<?php

namespace api\models\database\webetrela;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

/**
 * This is the model class for table "{{%user}}".
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
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $verification_token
 * @property string $access_token
 * @property string $branch
 * @property string|null $driver_name
 * @property string $position
 * @property int $branch_id
 * @property int|null $created_by
 * @property int|null $phone
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password_hash', 'email'], 'required'],
            [['status', 'created_at', 'updated_at', 'branch_id', 'created_by'], 'integer'],
            [['fullname', 'username', 'password_hash', 'pin', 'password_reset_token', 'email', 'verification_token', 'access_token', 'branch'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['auth_key', 'pin', 'access_token', 'branch', 'branch_id','driver_name','position','phone'], 'safe'],
            [['email'], 'unique'],
//            [['email'], 'unique', 'targetClass' => User::className(), 'message' => 'Пользователь уже существует'],
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
            'created_by' => Yii::t('app', 'Created By'),
            'driver_name' => Yii::t('app', 'Driver_name'),
            'position' => Yii::t('app', 'Position'),
            'phone' => Yii::t('app', 'Driver Phone'),
        ];
    }




    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
