<?php

use common\models\User;
use yii\rbac\DbManager;

$customer = \api\models\database\CustomersW::find()->all();

foreach ($customer as $c) {
    $user = new User();
    if($user->findByUsername($c->username)) {
  echo "User_exists";
    }
    else {
        $user->username = $c->username;
        $user->fullname = $c->name;
        $user->email = $c->email;
        $user->branch_id = 100;
        $user->branch = "Web";
        $user->status = 10;
        $user->access_token = Yii::$app->security->generateRandomString();
        $user->setPassword("ronnyspizza");
        $user->generateAuthKey();
        if ($user->save()) {
            $auth=new DbManager;
            $role = $auth->getRole('site_customer');
            $auth->assign($role, $user->getId());

            $customer_max  = \api\models\database\Customers::find()->where(["like","tel","%$c->tel%",false])->one();
            if (!$customer_max) {
                $customer_max = new \api\models\database\Customers();
                $customer_max->email = $c->email;
                $customer_max->user_id = $user->getId();
            } else {
                $customer_max->user_id = $user->getId();
            }
            $customer_max->save();

            echo  "Created - ".$user->getId();
        } else $result = "Coudn't add";
    }
}