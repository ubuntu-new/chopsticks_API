<?php
namespace rest\modules\v1\controllers;

use api\actions\BranchesAction;
use api\models\database\Branches;
use rest\controllers\RestController;
use rest\models\response\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use api\models\database\Branches_address;
use api\models\database\Branches_working_hours;


use api\models\response\Result;
use yii\base\Security;


/**
 * FaqController implements the CRUD actions for Faq model.
 */
class BranchesController extends RestController {
    public function actionList() {
//            $rcxa = \Yii::$app->request->post('id');
            return BranchesAction::getList();
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


    public function  actionCreate() {



        $product_id = \Yii::$app->request->post("product_id");
        $product_list= \Yii::$app->request->post("product_list");
        $child_product_id = \Yii::$app->request->post("child_product_id");

        $response = new Response();
        if(!$product_id) {
            return $this->errorResponse("missing parameter product_id",400);
        }


        $result = RecipesActions::create($product_id, $product_list, $child_product_id);

        return $result;
    }


}
