<?php
namespace rest\modules\v1\controllers;

use api\actions\Product_categoriesActions;
use api\models\database\Product_categories;
use rest\controllers\RestController;
use rest\models\response\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


use api\models\response\Result;
use yii\base\Security;


/**
 * FaqController implements the CRUD actions for Faq model.
 */
class Product_categoriesController extends RestController {
    public function actionList() {
//            $rcxa = \Yii::$app->request->post('id');
            return Product_categoriesActions::getList();
    }



}
