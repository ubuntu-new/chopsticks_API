<?php
namespace rest\modules\v1\controllers;

use api\actions\CustomersAction;
use api\actions\TimesheetAction;
use api\models\database\Customers;
use api\models\response\OrdersResponse;
use rest\controllers\RestController;
use rest\models\response\Response;
use yii\helpers\Json;

class TimesheetController extends RestController  {

    public function actionStart() {

        $response = new Response();

        $result = TimesheetAction::start();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'Operation failed' : '';
        $response->data = $result;
        return $response;
    }

    public function actionStartBreak() {

        $response = new Response();



        $result = TimesheetAction::startBreak();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'Operation failed' : '';
        $response->data = $result;
        return $response;
    }

    public function actionEndBreak() {

        $response = new Response();




        $result = TimesheetAction::endBreak();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'Operation failed' : '';
        $response->data = $result;
        return $response;
    }

    public function actionFinish() {

        $response = new Response();

        $result = TimesheetAction::finish();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'Operation failed' : '';
        $response->data = $result;
        return $response;
    }

    public function actionUsers() {
        $response = new Response();
        $result  = TimesheetAction::getActiveUsers();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'Operation failed' : '';
        $response->data = $result;
        return $response;

    }





}