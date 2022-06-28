<?php

namespace rest\modules\v1\controllers\webertela;

use api\actions\webertela\AboutActions;
use api\models\database\webetrela\Products;
use api\actions\webertela\ContacinfoActions;
use rest\controllers\RestController;
use yii\rest\Controller;
use rest\models\response\Response;

class AboutController extends RestController
{
    public function actionList()
    {
        $result = AboutActions::getList();

        $response = new Response();
//        if (!$category_id) {
//            $response->error_message = "Missing parameter: 'category_id'";
//            return $response;
//        }
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }



}
