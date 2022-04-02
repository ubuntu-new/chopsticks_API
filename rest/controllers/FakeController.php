<?php
namespace rest\controllers;

class FakeController extends LangController {

    public function actionOops() {
        $pin = \Yii::$app->request->post("pin");
        return $pin;
    }

}