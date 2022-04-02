<?php
namespace rest\modules\v1\models\profile;

use api\models\database\User;
use yii\base\Model;

class ProfileModel extends Model {
    const SCENARIO_CHANGE_NAME = 'changeName';
    const SCENARIO_CHANGE_PASSWORD = 'changePassword';

    public $first_name;
    public $last_name;
    public $old_password;
    public $password;
    public $password_repeat;

    public function rules() {
        return [
            [['first_name', 'last_name'], 'required', 'on' => self::SCENARIO_CHANGE_NAME],
            [['old_password', 'password', 'password_repeat'], 'required', 'on' => self::SCENARIO_CHANGE_PASSWORD],
            ['old_password', function($attribute, $params, $validator) {
                if (!\Yii::$app->user->identity->validatePassword($this->old_password))
                    $this->addError($attribute, \Yii::t('regOrg', 'You have entered wrong old password'));
            }],
            ['password', 'string', 'min' => 6],
            ['password', 'compare'],
            [['first_name', 'last_name', 'old_password', 'password', 'password_repeat'], 'trim']
        ];
    }

    public function changeName() {
        $user = User::findOne(['id' => \Yii::$app->user->id]);
        if (!$user)
            return false;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        return $user->save();
    }

    public function changePassword() {
        return \Yii::$app->user->identity->setPasswordAndSave($this->password);
    }
}