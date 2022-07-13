<?php
namespace rest\controllers;

use yii\filters\Cors;
use yii\rest\Controller;

class LangController extends Controller {
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

        return $behaviors;
    }

    public function beforeAction($action) {
        switch(\Yii::$app->request->get('lang')) {
            case 'ge':
                \Yii::$app->language = 'ka-GE';
                break;
            case 'ru':
                \Yii::$app->language = 'ru-RU';
                break;
            default:
                \Yii::$app->language = 'en-US';
        }

        return parent::beforeAction($action);
    }




}