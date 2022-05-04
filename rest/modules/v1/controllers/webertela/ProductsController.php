<?php

namespace rest\modules\v1\controllers\webertela;


use api\actions\webertela\ProductsActions;
use yii\rest\Controller;
use rest\models\response\Response;

class ProductsController extends Controller
{
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
            ],
        ];
    }
    public function actionList()
    {

        $category_id = \Yii::$app->request->post("category_id");

        $result = ProductsActions::getList($category_id);

        $response = new Response();
        if (!$category_id) {
            $response->error_message = "Missing parameter: 'category_id'";
            return $response;
        }

        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }



}
