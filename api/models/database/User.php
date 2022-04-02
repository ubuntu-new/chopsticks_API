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
class User extends ActiveRecord implements IdentityInterface {
    const MAGIC_USERID_LEN = 6;
    const MAGIC_ORGID_LEN = 3;
    const EVENT_ORG_NOT_ACTIVE = 'ORG_NOT_ACTIVE';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['status', 'default', 'value' => Status::getActive()]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        // return static::findOne(['id' => $id, 'status' => Status::getActive()]);
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        // return static::findOne(['access_token' => $token, 'status' => Status::getActive()]);
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = Yii::$app->getSecurity()->generateRandomString();
                $this->access_token = Yii::$app->getSecurity()->generateRandomString();
            }
            return true;
        }
        return false;
    }

    /**
     * Finds user by username.
     * If username contains at-sign, the authorization is held against 'email' column.
     * If username length is less than predefined max user_id length, the user is authorized against 'id' column.
     * If username length is more than predefined max user_id length, the username contains org_id info, and the
     * user is authorized against both user_id and org_id.
     * Only active users are allowed.
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        $status_active = Status::getActive();

        if (strpos($username, '@')) {
            return self::findWithCheckOrganizationStatus(static::find()->where(['email' => $username, 'status' => $status_active])->limit(1)->one());
            // return self::findWithCheckOrganizationStatus(static::findOne(['email' => $username, 'status' => $status_active]));
            // return static::findOne(['email' => $username, 'status' => $status_active]);
        }

        if (strlen($username) > self::MAGIC_USERID_LEN) {
            $u_part = substr($username, -self::MAGIC_USERID_LEN);
            $o_part = substr($username, 0, strlen($username) - self::MAGIC_USERID_LEN);
            $user_id = 1 * $u_part;
            $org_id = 1 * $o_part;
            $sql = "SELECT {{users}}.* FROM {{users}}
              INNER JOIN {{org_employees}} ON {{org_employees}}.[[user_id]] = {{users}}.[[id]]
              WHERE {{users}}.[[id]] = :user_id AND {{org_employees}}.[[org_id]] = :org_id
              AND {{users}}.[[status]] = :status_active AND {{org_employees}}.[[status]] = :status_active";
            // return static::findBySql($sql, [':user_id' => $user_id, ':org_id' => $org_id,
            // ':status_active' => $status_active])->limit(1)->one();
            return self::findWithCheckOrganizationStatus(static::findBySql($sql, [':user_id' => $user_id, ':org_id' => $org_id,
                ':status_active' => $status_active])->limit(1)->one());
        }

        $user_id = 1 * $username;

        // return json_encode(static::findOne(['id' => $user_id, 'status' => $status_active]));
        return self::findWithCheckOrganizationStatus(static::findOne(['id' => $user_id, 'status' => $status_active]));
    }

    public static function findByUsername2($username, $password) {
        $status_active = Status::getActive();

        if (strpos($username, '@')) {
            $users = static::find()->where(['email' => $username, 'status' => $status_active])->all();
            foreach ($users as $user) {
                if (Yii::$app->security->validatePassword($password, $user->password_hash)) {
                    return self::findWithCheckOrganizationStatus(static::find()->where(['id' => $user->id, 'status' => $status_active])->one());
                }
            }
        }

        if (strlen($username) > self::MAGIC_USERID_LEN) {
            $u_part = substr($username, -self::MAGIC_USERID_LEN);
            $o_part = substr($username, 0, strlen($username) - self::MAGIC_USERID_LEN);
            $user_id = 1 * $u_part;
            $org_id = 1 * $o_part;
            $sql = "SELECT {{users}}.* FROM {{users}}
              INNER JOIN {{org_employees}} ON {{org_employees}}.[[user_id]] = {{users}}.[[id]]
              WHERE {{users}}.[[id]] = :user_id AND {{org_employees}}.[[org_id]] = :org_id
              AND {{users}}.[[status]] = :status_active AND {{org_employees}}.[[status]] = :status_active";

            return self::findWithCheckOrganizationStatus(static::findBySql($sql, [':user_id' => $user_id, ':org_id' => $org_id,
                ':status_active' => $status_active])->limit(1)->one());
        }

        $user_id = 1 * $username;

        return self::findWithCheckOrganizationStatus(static::findOne(['id' => $user_id, 'status' => $status_active]));
    }

    public static function findWithCheckOrganizationStatus($user) {
        if ($user == null)
            return null;

        $sql = "SELECT CASE WHEN {{org}}.[[status]] IN
            (SELECT [[id]] FROM {{statuses}} WHERE [[status_key]] IN ('pending', 'blocked')) THEN 1 ELSE 0 END AS [[blocked]]
            FROM {{organizations}} {{org}}
            INNER JOIN {{org_employees}} {{emp}} ON {{emp}}.[[org_id]] = {{org}}.[[id]] AND {{emp}}.[[user_id]] = :user_id";
        $blocked = Yii::$app->db->createCommand($sql)->bindValue(':user_id', $user->id)->queryScalar();

        if (!$blocked || 1 * $blocked == 0)
            return $user;

        Event::trigger(User::className(), User::EVENT_ORG_NOT_ACTIVE);
        return null;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => Status::getActive(),
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    /**
     * Full name of user
     *
     * @return string
     */
    public function getUserFullName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Updates user's password and stores immediately
     *
     * @param $pass
     * @return bool
     */
    public function setPasswordAndSave($pass) {
        $this->setPassword($pass);
        return $this->save();
    }

    /**
     * Constructs username of pattern {org_id}{user_id} with each part od magic length filled with leading zeros
     *
     * @param int $org_id
     * @return string
     */
    public function getConstructedUsername($org_id = 0) {
        $username = str_pad('' . $this->id, self::MAGIC_USERID_LEN, '0', STR_PAD_LEFT);
        if ($org_id > 0) {
            $username = str_pad('' . $org_id, self::MAGIC_ORGID_LEN, '0', STR_PAD_LEFT);
        }
        return $username;
    }

    /**
     * Gets current user's avatar from default CV
     *
     * @return bool|string
     */
    public function getAvatar() {
        return (new Query())->select('profile_photo')->from('user_cv')
            ->where(['user_id' => $this->id, 'is_default' => true])
            ->limit(1)->scalar();
    }

    /**
     * Gets current user's small avatar from default CV
     *
     * @return bool|string
     */
    public function getAvatarSmall() {
        return (new Query())->select('profile_photo_small')->from('user_cv')
            ->where(['user_id' => $this->id, 'is_default' => true])
            ->limit(1)->scalar();
    }

    /**
     * Gets current user's sex (0 - feminine, 1 - masculine)
     *
     * @return integer|null
     */
    public function getSex() {
        return (new Query())->select('sex')->from('user_cv')
            ->where(['user_id' => $this->id, 'is_default' => true])
            ->limit(1)->scalar();
    }

    /**
     * Gets current user's profession
     *
     * @return string
     */
    public function getProfession() {
        $profession = UserDataExtra::findOne(['user_id' => $this->id]);
        return ($profession) ? $profession->profession : '';
    }

    /**
     * Gets current user's post
     *
     * @return string
     */
    public function getOrgPost() {
        $sql = "SELECT {{op}}.[[title]]
                FROM {{org_posts_users}} {{opu}}
                INNER JOIN {{org_posts}} {{op}} ON {{op}}.[[id]] = {{opu}}.[[org_post_id]]
                WHERE {{opu}}.[[user_id]] = :user_id AND {{op}}.[[status]] = :status_active";
        $post = \Yii::$app->db->createCommand($sql, [':user_id' => $this->id, ':status_active' => Status::getActive()])->
            queryScalar();
        return ($post) ? $post : '';
    }


}