<?php
namespace frontend\controllers;

use api\actions\OrdersActions;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class PrintController extends Controller
{



    public function actionIndex()
    {


        return $this->render('index');
    }

    public function actionMenu() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return$this->render("menu");

    }


}
