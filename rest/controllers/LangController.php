<?php
namespace rest\controllers;

use yii\filters\Cors;
use yii\rest\Controller;

class LangController extends Controller {

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