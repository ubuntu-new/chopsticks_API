<?php
namespace rest\modules\v1\controllers;

use api\actions\CustomersAction;
use api\actions\PosesAction;
use api\models\database\Banks;
use api\models\database\Customers;
use api\models\database\Poses;
use api\models\database\PosesBalanceDetail;
use api\models\database\Safe;
use api\models\database\SafeBalance;
use api\models\database\SafeBalanceDetail;
use api\models\database\Status;
use api\models\response\OrdersResponse;
use rest\controllers\RestController;
use rest\models\response\Response;
use yii\base\Controller;
use yii\helpers\Json;

class PosesController extends RestController  {

    public function actionBranchList() {

        $response = new Response();

        $result = PosesAction::getBranchList();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionPosesByMac() {

        $mac =  \Yii::$app->request->post('mac');
        $response = new Response();
        if (!$mac) {
            $response->error_message = "Missing parameter: 'Mac'";
            return $response;
        }

        $result = PosesAction::getPosesByMac($mac);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionList() {
        $response = new Response();

        $result = PosesAction::getPosesList();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionListByBranch() {
        $branch_id = \Yii::$app->request->post("branch_id");
        $response = new Response();
        if (!$branch_id) {
            $response->error_message = "Missing parameter: 'Branch_id'";
            return $response;
        }

        $result = PosesAction::getPosesListByBranch($branch_id);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionEditBalance() {

        $pos_id =  \Yii::$app->request->post('pos_id');
        $amount =  \Yii::$app->request->post('amount');

        $response = new Response();
        if (!$pos_id) {
            $response->error_message = "Missing parameter: 'pos id'";
            return $response;
        }

        if (!isset($amount)) {
            $response->error_message = "Missing parameter: 'Amount'";
            return $response;
        }

        $result = PosesAction::EditPosBalance($pos_id,$amount);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionSales() {
        $response = new Response();
        $branch_id = \Yii::$app->request->post("branch_id");
        $day = \Yii::$app->request->post("day");

        if (!$branch_id) {
            $response->error_message = "Missing parameter: 'branch_id'";
            return $response;
        }

        if (!$day) {
            $response->error_message = "Missing parameter: 'Day'";
            return $response;
        }

        $result = PosesAction::getSalesByPos($branch_id, $day);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionDropSafeBalance() {
        $safe_id =  \Yii::$app->request->post('safe_id');
        $amount =  \Yii::$app->request->post('amount');
        $bank_id =  \Yii::$app->request->post('bank_id');
        $comment =  \Yii::$app->request->post('comment');

        $response = new Response();
        if (!$safe_id) {
            $response->error_message = "Missing parameter: 'safe id'";
            return $response;
        }
        if (!$bank_id) {
            $response->error_message = "Missing parameter: 'bank id'";
            return $response;
        }

        if (!$amount) {
            $response->error_message = "Missing parameter: 'Amount'";
            return $response;
        }

        $result = PosesAction::dropBalanceFormSafe($safe_id,$amount, $bank_id, $comment);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionExpenses() {
        $safe_id =  1;
        $amount =  \Yii::$app->request->post('amount');
        $comment =  \Yii::$app->request->post('comment');

        $response = new Response();
        if (!$safe_id) {
            $response->error_message = "Missing parameter: 'safe id'";
            return $response;
        }


        if (!$amount) {
            $response->error_message = "Missing parameter: 'Amount'";
            return $response;
        }

        $result = PosesAction::dropExpenses($safe_id,$amount, $comment);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionGetSafes() {
        $branch_id = \Yii::$app->request->post("branch_id");
        $day = \Yii::$app->request->post("day");

        $response = new Response();
        if (!$branch_id) {
            $response->error_message = "Missing parameter: 'branch_id'";
            return $response;
        }

    if (!$day) {
            $response->error_message = "Missing parameter: 'day'";
            return $response;
        }

        $result = PosesAction::getSafe($branch_id, $day);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionSafeDetail() {
       // $safe_id = \Yii::$app->request->post("safe_id");
        $day = \Yii::$app->request->post("day");
      //  $day = explode("to", $day);


        $response = new Response();
      /*  if (!$safe_id) {
            $response->error_message = "Missing parameter: 'safe_id'";
            return $response;
        }*/

        $result = PosesAction::getSafeDetail($day);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionBankList() {
        $response = new Response();
        $result = Banks::find()->all();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

/*    public function actionAddBalanceToSafe() {
        $pos_id = \Yii::$app->request->post("pos_id");
        $amount = \Yii::$app->request->post("amount");
        $safe_id =  \Yii::$app->request->post('safe_id');

        $response = new Response();
        if (!$pos_id) {
            $response->error_message = "Missing parameter: 'pos id'";
            return $response;
        }

        if (!$amount) {
            $response->error_message = "Missing parameter: 'Amount'";
            return $response;
        }
        if (!$safe_id) {
            $response->error_message = "Missing parameter: 'safe id'";
            return $response;
        }

        $result = PosesAction::AddbalanceToSafe($safe_id,$pos_id,$amount);

            if($amount > 0)
                PosesAction::AddbalanceToPos($pos_id,(-1*$amount));
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }*/

    /*public function actionEditSafeBalance() {

        $amount = \Yii::$app->request->post("amount");
        $safe_id =  \Yii::$app->request->post('safe_id');

        $response = new Response();

        if (!$amount) {
            $response->error_message = "Missing parameter: 'Amount'";
            return $response;
        }
        if (!$safe_id) {
            $response->error_message = "Missing parameter: '$safe_id'";
            return $response;
        }

        $result = PosesAction::dropSafeBalance($safe_id,$amount);

        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }*/

    public static function getSafeBalance($safe_id= null) {
        $data = date("Y-m-d");
        $drivers_balance = SafeBalance::find()->where(["safe"=>$safe_id])->andWhere(["like","created_at", $data."%", false])->one();
        if ($drivers_balance)
            return $drivers_balance->amount;
        else return 0;


    }

    public function actionGetPoses() {
        $branch_id = \Yii::$app->request->post("branch_id");
        $day = \Yii::$app->request->post("day");

        $response = new Response();
        if (!$branch_id) {
            $response->error_message = "Missing parameter: 'branch_id'";
            return $response;
        }

        $result = PosesAction::getPoses($branch_id, $day);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionPosesDetail() {
        //$pos_id = \Yii::$app->request->post("pos_id");
        $day = \Yii::$app->request->post("day");
        $day = explode("to", $day);

        $response = new Response();
        /*if (!$pos_id) {
            $response->error_message = "Missing parameter: 'pos_id'";
            return $response;
        }*/

        if (!$day) {
            $response->error_message = "Missing parameter: 'day'";
            return $response;
        }

       /* $result = PosesBalanceDetail::find()->joinWith()->where(["pos_id"=>$pos_id])
            ->andWhere(["LIKE","created_at", $day."%", false])->all();*/

        $result = PosesBalanceDetail::find()->select('poses_balance_detail.*,poses.name')  // make sure same column name not there in both table
        ->leftJoin('poses', 'poses.id = poses_balance_detail.pos_id')

               ->Where([">","poses_balance_detail.created_at", trim($day[0])." 00:00:00"])
               ->andWhere(["<","poses_balance_detail.created_at", trim($day[1])." 23:59:59"])
            ->asArray()
            ->all();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionMyBalance(){

        $response = new Response();
        $result = PosesAction::GetMyBalance();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;

    }

    public function actionPaymentMethods() {
        $response = new Response();
        $result = PosesAction::paymentMethods();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionDeliveryMethods() {
        $response = new Response();
        $result = PosesAction::deliveryMethods();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionOrderStatuses() {
        $response = new Response();
        $result = PosesAction::OrderStatuses();
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }





}