<?php
namespace rest\modules\v1\controllers;

use api\actions\RecipesActions;
use api\models\database\Recipes;
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
class RecipesController extends RestController {
    public function actionList() {
//            $rcxa = \Yii::$app->request->post('id');
            return RecipesActions::getList();
    }


    public function actionId() {

        $post = \Yii::$app->request->post();
        if (!isset($post['product_id']) || empty($post['product_id']))
            return $this->errorResponse("missing parameter product_id",400);


        return RecipesActions::getId($post["product_id"]);
    }

    public function  actionCreateOld() {




            $id = \Yii::$app->request->post("id");
            $product_id = \Yii::$app->request->post("product_id");
            $parent = \Yii::$app->request->post("parent");
            $child = \Yii::$app->request->post("child");
            $unit = \Yii::$app->request->post("unit");
            $qty = \Yii::$app->request->post("qty");
            $small = \Yii::$app->request->post("small");
            $large = \Yii::$app->request->post("large");
            $recipe_result_min = \Yii::$app->request->post("recipe_result_min");
            $recipes_result_max = \Yii::$app->request->post("recipes_result_max");

            $response = new Response();
            if(!$id) {
                return $this->errorResponse("missing parameter id",400);
                return $response;
            }
            if(!$product_id){
                return $this->errorResponse("missing parameter product_id",400);
                return $response;
            }
            if(!$parent){
                return $this->errorResponse("missing parameter parent",400);
                return $response;
            }
            if(!$product_id){
                return $this->errorResponse("missing parameter product_id",400);
                return $response;
            }
            if(!$product_id){
                return $this->errorResponse("missing parameter product_id",400);
                return $response;
            }
            if(!$product_id){
                return $this->errorResponse("missing parameter product_id",400);
                return $response;
            }
            if(!$product_id){
                return $this->errorResponse("missing parameter product_id",400);
                return $response;
            }
            if(!$product_id){
                return $this->errorResponse("missing parameter product_id",400);
                return $response;
            }
            if(!$product_id){
                return $this->errorResponse("missing parameter product_id",400);
                return $response;
            }

            $result = RecipesActions::create($product_id, $parent,$child,$unit,$qty,$small,$large,$recipe_result_min,$recipes_result_max);

            return $result;
        }

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
