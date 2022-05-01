<?php
namespace rest\modules\v1\controllers;

use api\actions\Product_categories_idAction;
use api\models\database\Products;
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
class Products_categories_idController extends RestController {


    public function actionList($category_id = null) {
        $category_id = \Yii::$app->request->post("category_id");
        $response = new Response();
        if(!$category_id) {
            return $this->errorResponse("missing parameter category_id",400);
            return $response;
        }
        return Product_categories_idAction::getList($category_id);
    }




}
