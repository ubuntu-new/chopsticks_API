<?php
/**
 * Created by PhpStorm.
 * User: levan
 * Date: 4/13/2020
 * Time: 12:42
 */

namespace api\actions;


use api\models\database\Customers;
use api\models\response\SiteCustomerResponse;
use mdm\admin\models\User;
use yii\rbac\DbManager;
use Yii;

class userActions
{
    public static function getBranch(){
        if (\Yii::$app->user->isGuest) {
            return null;
        } else {
            $model = User::find()->where(["id"=>\Yii::$app->user->id])->one();
            return $model->branch;

        }
    }

    public static function signupUser($username=null, $email = null, $password = null) {
        $result = null;
        $user = new User();
        if($user->findByUsername($username) || $user->findByEmail($email)) {
            $result = "User_exists";
        }
        else {
            $user->username = $username;
            $user->email = $email;
            $user->branch_id = 100;
            $user->branch = "Web";
            $user->status = 10;
            $user->access_token = \Yii::$app->security->generateRandomString();
            $user->setPassword($password);
            $user->generateAuthKey();
            if ($user->save()) {
                $auth=new DbManager;
                $role = $auth->getRole('site_customer');
                $auth->assign($role, $user->getId());
                $result =   "Created - ".$user->getId();
            } else $result = "Coudn't add";
        }

    return $result;
    }

    public static function updateUser($id = null, $name = null, $address = null, $tel = null, $comment = null, $comment2 = null, $b_day = null, $gender = null, $email = null, $discount = null,  $personal_id = null, $ltd_id = null, $ltd_name = null) {
        $customer =  Customers::find()->andFilterWhere(['user_id'=>$id])->one();
        if ($customer) {
            $customer->user_id = $id;
            $customer->name = $name;
            $customer->address = serialize($address);
            $customer->tel = serialize($tel);
            $customer->comment = $comment;
            $customer->comment2 = $comment2;
            $customer->b_day = $b_day;
            $customer->gender = $gender;
            $customer->email = $email;
            $customer->discount = $discount;
            $customer->personal_id = $personal_id;
            $customer->ltd_id = $ltd_id;
            $customer->ltd_name = $ltd_name;
            if ($customer->save()) {
                $result = Yii::$app->response->statusCode = 200;
                return $result;
            }
        }
        else {
            $customer = new Customers();
            if (!$id)
                return false;
            $customer->user_id = $id;
            $customer->name = $name;
            $customer->address = serialize($address);
            $customer->tel = serialize($tel);
            $customer->comment = $comment;
            $customer->comment2 = $comment2;
            $customer->b_day = $b_day;
            $customer->gender = $gender;
            $customer->email = $email;
            $customer->discount = $discount;
            $customer->personal_id = $personal_id;
            $customer->ltd_id = $ltd_id;
            $customer->ltd_name = $ltd_name;
            if ($customer->save()) {
                $result = Yii::$app->response->statusCode = 201;
                return $result;
            }
        }

    }


    public static function infoUser($id = null)
    {
        $customer = Customers::find()
            ->where(['user_id' => $id])
            ->one();

        return $customer;
    }

}