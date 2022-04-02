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
class OrdersController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionGetOrders()
    {
        $branch =  Yii::$app->request->post("branch") ? Yii::$app->request->post("branch"): null;
        return Json::encode(OrdersActions::getGetOrders($branch));
    }

    public function actionUpdateOrderStatus()
    {
       $duration =  Yii::$app->request->post("duration");
       return OrdersActions::updateOrderStatus(Yii::$app->request->post("order_id"), Yii::$app->request->post("status"), $duration);
    }
    public function actionCancelOrder()
    {

       return OrdersActions::cancelOrder(Yii::$app->request->post("order_id"), Yii::$app->request->post("text"), Yii::$app->request->post("user"), Yii::$app->request->post("mail"));
    }
       public function actionChangeOrderAddress()
    {

       return OrdersActions::changeOrderAddress(Yii::$app->request->post("order_id"), Yii::$app->request->post("method_id"), Yii::$app->request->post("address"));
    }



}
