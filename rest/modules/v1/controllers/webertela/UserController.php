<?php
/**
 * Created by PhpStorm.
 * User: levan
 * Date: 04/17/22
 * Time: 18:59
 */

namespace rest\modules\v1\controllers\webertela;


use api\models\database\webetrela\User;
use yii\rest\ActiveController;
use yii\rbac\DbManager;

class UserController extends ActiveController
{
    public $modelClass = User::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        unset($actions['update']);
        return $actions;
    }

    public function actionCreate($username, $password){
        $result = null;
        $security = \Yii::$app->security;


        $user = New User();

        $user->username = $username;
        $user->password_hash = $security->generatePasswordHash($password);
        $user->access_token =  $security->generateRandomString(255);
        $user->branch =  "web";
        if ($user->save()) {
            $auth=new DbManager;
            $role = $auth->getRole('site_customer');
            $auth->assign($role, $user->getId());


            return  "Created - ".$user->getId()." status- ".\Yii::$app->response->statusCode = 200;
        } else
        {
            var_dump($user->errors);
            return "Coudn't Create" ;
        }
    }


    public function actionUpdate($id, $username, $email){



        if($email) {
            $mailer = Yii::createComponent('application.extensions.mailer.EMailer');
            $mailer->IsSMTP();
            $mailer->IsHTML(true);
            $mailer->SMTPAuth = true;
            $mailer->SMTPSecure = "ssl";
            $mailer->Host = "smtp.gmail.com";
            $mailer->Port = 465;
            $mailer->CharSet = 'UTF-8';
            $mailer->Username = "levan.andguladze@gmail.com";
            $mailer->Password = "missvasasi";
            $mailer->From = "noreply@chopsticks.com";
            $mailer->FromName = "HQ-DEV-01";
            $mailer->AddAddress($_POST['email']);
            $mailer->Subject = "welcome to CES Document Site";
            $mailer->IsHTML(true);
            $mailer->Body = "<h1>Thanks, please</h1><br>
                          click on link for other detail
                          " . $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

            if ($mailer->Send()) {
                echo "Please check your email";
            } else {
                echo "Fail to send your message!";
            }
        }

    }


    public static function actionPlainPassword($id, $password) {
        if (\Yii::$app->user->identity->validatePassword($password)) {
            if ($id = User::find()->where(['id' => $id])->limit(1)->one())
                return ['success' => true, 'pass' => $id->plain_pass];
        }
        return ['success' => false, 'pass' => ''];
    }


    public function actionChangePass($username=null)
    {
        $customer =  User::find()->andFilterWhere(['username'=>$username])->one();

        $user = loadModel($username);
    if(md5($_POST['User']['old_password']) === $user->password)
    {
        $user->setScenario('changePassword');
        $user->attributes = $_POST['User'];
        $user->password = md5($_POST['User']['new_password']);
        if($user->save())
            Yii::app()->user->setFlash('passChanged', 'Your password has been changed <strong>successfully</strong>.');
    }
    else
    {
        Yii::app()->user->setFlash('passChangeError', 'Your password was not changed because it did not matched the <strong>old password</strong>.');
    }
 }
}

//$security = \Yii::$app->security;
//
//$user = New User();
//$user->username = $username;
//$user->password_hash = $security->generatePasswordHash($password);
//$user->access_token =  $security->generateRandomString(255);
//
//if ($user ->save()){
////            define("STDOUT", fopen('log.txt', 'w'));
//    Console::output("Saved");
//}
//else
//{
//    define("STDOUT", fopen('log.txt', 'w'));
//    var_dump($user -> errors);
//    var_dump(PHP_SAPI);
//    Console::output("Not Saved");
//}

