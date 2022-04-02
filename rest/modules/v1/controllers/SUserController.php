<?php
namespace rest\modules\v1\controllers;


use api\actions\userActions;
use api\models\database\Customers;
use rest\controllers\RestController;
use rest\models\response\Response;
use api\models\database\CustomersW;
use api\models\database\SUser;
use api\models\database\User;
use Yii;
use yii\web\Controller;

class SUserController extends RestController  {



    public function actionSignup() {

        $username = Yii::$app->request->post("username");
        $email = Yii::$app->request->post("email");
        $password = Yii::$app->request->post("password");

        return userActions::signupUser($username, $email, $password);

    }


    public function actionUpdate()
    {
        $id = Yii::$app->request->post("id");
        $name = Yii::$app->request->post("name");
        $address = Yii::$app->request->post("address");
        $tel = Yii::$app->request->post("tel");
        $comment = Yii::$app->request->post("comment");
        $comment2 = Yii::$app->request->post("comment2");
        $b_day = Yii::$app->request->post("b_day");
        $gender = Yii::$app->request->post("gender");
        $email = Yii::$app->request->post("email");
        $discount = Yii::$app->request->post("discount");
        $personal_id = Yii::$app->request->post("personal_id");
        $ltd_id = Yii::$app->request->post("ltd_id");
        $ltd_name = Yii::$app->request->post("ltd_name");

        $response = new Response();
        if (!$id) {
            $response->error_message = "Missing parameter: 'id'";
            return $response;
        }
        return userActions::updateUser($id, $name, $address, $tel, $comment, $comment2, $b_day, $gender, $email, $discount, $personal_id, $ltd_id, $ltd_name);

    }



    public function actionInfo()
    {
        $id = Yii::$app->request->post("id");
        return userActions::infoUser($id);

    }

}