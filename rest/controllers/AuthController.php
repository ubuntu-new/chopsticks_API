<?php
namespace rest\controllers;

use api\actions\UserAction;
use api\actions\UserPasswordResetAction;
use api\models\database\Poses;
use api\models\database\Safe;
use api\models\database\Timesheet;
use api\models\database\Warehouses;
use rest\models\response\Response;
use rest\models\response\TokenInfo;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\User;

class AuthController extends LangController {

    public $enableCsrfValidation = false;
    public function behaviors() {

        $behaviors['cors'] = ['class' => Cors::class];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            /* 'authMethods' => [
                 HttpBasicAuth::class,
                 HttpBearerAuth::class,
                 QueryParamAuth::class
             ]*/
        ];
        return $behaviors;
    }



    /*public function actionIndex() {
        $post = \Yii::$app->request->post();

        if (!isset($post['username']) || empty($post['username']))
            throw new HttpException(422, "Missing parameter: 'username'");
        if (!isset($post['password']) || empty($post['password']))
            throw new HttpException(422, "Missing parameter: 'password'");

        $token = UserAction::tryToFetchAccessToken($post['username'], $post['password']);
        if (!$token)
            throw new HttpException(401, "Authentication Failed!");
        return ['token' => $token];
    }*/

    public function actionIndex() {


        $response = new Response();
        if (\Yii::$app->request->post("pin")) {
            if (\Yii::$app->request->post("mac")) {
                $mac = \Yii::$app->request->post("mac");
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
                $macCommandString = "arp $ip | awk 'BEGIN{ i=1; } { i++; if(i==3) print $3 }'"; // awk command to crawl mac from string

                $mac = exec($macCommandString);
            }



            $poses = Poses::find()->where(["mac"=>$mac])->one();

            $user = UserAction::tryToFetchAccessToken(null, $mac, trim(\Yii::$app->request->post("pin")), $poses);
            if ($user)
                $safe_id = Safe::find()->where(["branch"=>$user->branch])->one()->id;
        }
        else {
            $username = trim(\Yii::$app->request->post('username'));
            $password = trim(\Yii::$app->request->post('password'));


            if (!$username) {
                $response->error_message = "Missing parameter: 'username'";
                return $response;
            }

            if (!$password) {
                $response->error_message = "Missing parameter: 'password'";
                return $response;
            }

            $user = UserAction::tryToFetchAccessToken($username, $password);
        }

        if (!$user) {
            $response->error_message = 'Incorrect username or password';
            return $response;
        }


        $dir = "";
        $token_info = new TokenInfo();
        $token_info->token = $user->access_token;
        $token_info->id = $user->id;
        $token_info->first_name = $user->username;
        $token_info->branch_name = $user->branch;
        $token_info->branch_id = $user->branch_id;
        $local_warehouse = Warehouses::find()->where(["branch_id"=>$user->branch_id])->one();
        if (\Yii::$app->request->post("pin")) {
            $token_info->pos_id = $poses->id;
            $token_info->safe_id = $safe_id;


        }

        $token_info->email = $user->email;
        $token_info->warehouseId = $local_warehouse->id;

        $roleModel = \Yii::$app->db
            ->createCommand("SELECT * from auth_assignment where user_id=".$user->id." ")
            ->queryOne();
        $token_info->role = trim($roleModel['item_name']);

        $response->is_error = false;
        $response->data = $token_info;
        return $response;
    }


    public function actionClocked() {
        $response = new Response();
        if (\Yii::$app->request->post("pin")) {

            $user = UserAction::tryToFetchAccessTokenClocked(trim(\Yii::$app->request->post("pin")));

        }


        if (!$user) {
            $response->error_message = 'Incorrect username or password';
            return $response;
        }

        $timesheet = Timesheet::find()->where(["user_id"=>$user->id])->andWhere(["end_date"=>""])->orderBy(['id' => SORT_DESC])->one();


        $dir = "";
        $token_info = new TokenInfo();
        $token_info->token = $user->access_token;
        $token_info->id = $user->id;
        $token_info->first_name = $user->username;
        $token_info->email = $user->email;
        $token_info->clocked = $timesheet?true:false;
        $token_info->state = $timesheet?$timesheet->state:"FINISH";
        $token_info->timesheet_date = $timesheet?$timesheet->created_at:null;
        $roleModel = \Yii::$app->db
            ->createCommand("Select * from auth_assignment where user_id='$user->id'")
            ->queryOne();
        $token_info->role = $roleModel['item_name'];

        $response->is_error = false;
        $response->data = $token_info;
        return $response;
    }

    public function actionResetPasswordCode() {
        $email = trim(\Yii::$app->request->post('email'));
        $program = trim(\Yii::$app->request->post('program'));
        $response = new Response();

        if (!$email) {
            $response->error_message = "Missing parameter: 'email'";
            return $response;
        }

        if (!$program) {
            $response->error_message = "Missing parameter: 'program'";
            return $response;
        }

        $response->is_error = !UserPasswordResetAction::requestPasswordResetCode($email, $program);
        return $response;
    }

    public function actionResetPassword() {
        $email = trim(\Yii::$app->request->post('email'));
        $code = trim(\Yii::$app->request->post('code'));
        $password = trim(\Yii::$app->request->post('password'));
        $password_repeat = trim(\Yii::$app->request->post('password_repeat'));
        $response = new Response();

        if (!$email) {
            $response->error_message = "Missing parameter: 'email'";
            return $response;
        }

        if (!$code) {
            $response->error_message = "Missing parameter: 'code'";
            return $response;
        }

        if (!$password) {
            $response->error_message = "Missing parameter: 'password'";
            return $response;
        }

        if (!$password_repeat) {
            $response->error_message = "Missing parameter: 'password repeat'";
            return $response;
        }

        if ($password != $password_repeat) {
            $response->error_message = "Passwords do not match";
            return $response;
        }

        $result = UserPasswordResetAction::processPasswordReset($email, $code, $password);
        $response->is_error = !$result;
        $response->error_message = (!$result) ? \Yii::t('Notifications', 'Operation failed') : '';
        return $response;
    }

}