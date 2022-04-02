<?php
namespace rest\controllers;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\Cors;
use yii\rest\Controller;

class RestController extends LangController {

    public function errorResponse($message,$code) {
        \Yii::$app->response->statusCode = $code;
        if ($code>=200 && $code<300)
            return $this->asJson(['data' => $message]);
        if ($code>=300 && $code<400)
              return $this->asJson(['error' => $message]);
        if ($code>=400 && $code<500)
            return $this->asJson(['error' => $message]);
    }

    public function behaviors() {

        $behaviors['cors'] = ['class' => Cors::class];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBasicAuth::class,
                HttpBearerAuth::class,
                QueryParamAuth::class
            ]
        ];
        return $behaviors;
    }


}