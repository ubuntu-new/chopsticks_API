<?php
namespace rest\modules\v1\controllers;

use api\actions\OrdersSiteAction;
use api\models\database\Orders;
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
class OrdersSiteController extends RestController {

    public function actionList($user_id = null) {
        $user_id = \Yii::$app->request->post("user_id");
        $response = new Response();
        if(!$user_id) {
            return $this->errorResponse("missing parameter user_id",400);
            return $response;
        }
            return OrdersSiteAction::getList($user_id);
    }

//
//    public function actionId() {
//
//        $post = \Yii::$app->request->post();
//        if (!isset($post['product_id']) || empty($post['product_id']))
//            return $this->errorResponse("missing parameter product_id",400);
//
//
//        return RecipesActions::getId($post["product_id"]);
//    }


//    public function  actionCreate() {
//
//
//
//        $product_id = \Yii::$app->request->post("product_id");
//        $product_list= \Yii::$app->request->post("product_list");
//        $child_product_id = \Yii::$app->request->post("child_product_id");
//
//        $response = new Response();
//        if(!$product_id) {
//            return $this->errorResponse("missing parameter product_id",400);
//        }
//
//
//        $result = RecipesActions::create($product_id, $product_list, $child_product_id);
//
//        return $result;
//    }


}
