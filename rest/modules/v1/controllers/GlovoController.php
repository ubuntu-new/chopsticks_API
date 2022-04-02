<?php
namespace rest\modules\v1\controllers;

use api\actions\UserAction;
use api\models\database\IngredientsPrice;
use api\models\response\Result;
use rest\controllers\RestController;
use yii\base\Security;
use rest\models\response\Response;

class GlovoController extends RestController  {




    public function actionCreateOrder() {
        $order =  \Opis\Closure\serialize(\Yii::$app->request->post('order'));
        return UserAction::recieveOrderGlovo($order,'Glovo');
    }

    public function actionCancelOrder() {
        $order =  \Opis\Closure\serialize(\Yii::$app->request->post('order'));
        return UserAction::recieveOrder($order,'GlovoCancel');
    }


}