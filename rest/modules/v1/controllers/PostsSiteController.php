<?php
namespace rest\modules\v1\controllers;

use api\actions\OrdersActions;
use api\actions\PostsSiteAction;
use api\models\database\Posts;
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
class PostsSiteController extends RestController {
    public function actionList() {

        return PostsSiteAction::getList();
    }

    public function actionCreatePost() {

        $response = new Response();

        $id =  \Yii::$app->request->post('id');
        $post_author =  \Yii::$app->request->post('post_author');
        $post_content = \Yii::$app->request->post("post_content");
        $post_title = \Yii::$app->request->post("post_title");
        $post_excerpt = \Yii::$app->request->post("post_excerpt");
        $post_status = \Yii::$app->request->post("post_status");
        $post_name = \Yii::$app->request->post("post_name");
        $post_type = \Yii::$app->request->post("post_type");
        $view = \Yii::$app->request->post("view");
        $post_tags = \Yii::$app->request->post("post_tags");
        $path =
        $image = \Yii::$app->request->post("image");

        if (!$post_title) {
            $response->error_message = "Missing parameter: 'post_title'";
            return $response;
        }

        if (!$post_content) {
            $response->error_message = "Missing parameter: 'post_content'";
            return $response;
        }


        $result = PostsSiteAction::createPost($id, $post_author, $post_content, $post_title, $post_excerpt, $post_status, $post_name, $post_type, $view, \Opis\Closure\serialize($post_tags), $image);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'Operation failed' : '';
        $response->data = $result;
        return $response;
    }

}
