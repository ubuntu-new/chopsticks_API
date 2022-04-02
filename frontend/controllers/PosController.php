<?php
namespace frontend\controllers;

use api\actions\OrdersActions;
use api\models\database\Products;
use app\models\Ingredients;
use app\models\Pcategory;


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
class PosController extends Controller
{
    public function actionIndex() {
        $categories = Pcategory::find()->orderBy("weight")->all();
        $drinks = Products::find()->where(["category_name"=>"Drinks"])->all();
        $pizzas = Products::find()->where(["category_name"=>"Pizza"])->all();
        $extras = Products::find()->where(["category_name"=>"extras"])->all();
        $ingredients = Ingredients::find()->all();

        return $this->render("index",[
            'categories'=>$categories,
            'drinks'=>$drinks,
            'pizzas'=>$pizzas,
            'extras'=>$extras,
            'ingredients'=>$ingredients
        ]);
    }

    public function actionInsert()
    {

        return $this->render('insert');

    }
}
