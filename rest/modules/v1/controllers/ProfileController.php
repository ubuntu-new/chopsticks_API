<?php
namespace rest\modules\v1\controllers;

use api\models\database\User;
use api\models\database\UserCv;
use rest\controllers\RestController;
use rest\models\response\Response;
use rest\modules\v1\models\profile\ProfileModel;
use rest\modules\v1\models\profile\UserModel;
use yii\imagine\Image;

class ProfileController extends RestController {

    public function actionChangeName() {
        $response = new Response();
        $model = new ProfileModel(['scenario' => ProfileModel::SCENARIO_CHANGE_NAME]);
        $model->attributes = \Yii::$app->request->post();
        if ($model->validate()) {
            if ($model->changeName()) {
                $response->is_error = false;
            } else {
                $response->error_message = \Yii::t('Notifications', 'Operation failed');
            }
        } else {
            $first_error_key = array_keys($model->errors)[0];
            $response->error_message = $model->getFirstError($first_error_key);
        }
        return $response;
    }

    public function actionChangePassword() {
        $response = new Response();
        $model = new ProfileModel(['scenario' => ProfileModel::SCENARIO_CHANGE_PASSWORD]);
        $model->attributes = \Yii::$app->request->post();
        if ($model->validate()) {
            if ($model->changePassword()) {
                $response->is_error = false;
            } else {
                $response->error_message = \Yii::t('Notifications', 'Operation failed');
            }
        } else {
            $first_error_key = array_keys($model->errors)[0];
            $response->error_message = $model->getFirstError($first_error_key);
        }
        return $response;
    }

    public function actionChangePin() {
        $post = \Yii::$app->request->post();
        $response = new Response();
        $pin = $post["pin"];
        $get_user_id = $post["user_id"];
        $user_id = $get_user_id? $get_user_id:\Yii::$app->user->getId();
        if (!$pin) {
            $response->is_error = true;
            $response->error_message = "Missing parameter: 'pin'";
            return $response;
        }

        $user = User::find()->where(["id"=>$user_id])->one();
        if ($user) {
            $checkPin = User::find()->where(["<>","id",$user_id])->andWhere(["pin"=>$pin])->one();
            if (!$checkPin) {
                $user->pin = $pin;
                if($user->save()) {
                    $response->is_error = false;
                    $response->data = "Pin Changed successfully";
                } else {
                    $response->is_error = true;
                    $response->error_message = "Something went wrong";
                }


            } else {
                $response->is_error = true;
                $response->error_message = "Something went wrong user other pin";
            }
        }

        return $response;
    }

}