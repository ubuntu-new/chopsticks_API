<?php
namespace rest\modules\v1\controllers;


use api\actions\WarehousesActions;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use rest\controllers\RestController;
use yii\web\HttpException;


class WarehousesController extends RestController  {


    public function actionWarehouseList() {

        $result = WarehousesActions::WarehouseList();
        return $result;
    }

    public function actionSuppliesList() {
        $post = \Yii::$app->request->post();


        $result = WarehousesActions::SuppliesList($post["warehouse_id"]);
        return $result;
    }

    public function actionWarehouseUnit() {

        $result = WarehousesActions::WarehouseUnit();
        return $result;
    }

    public function actionCreateUnit() {
        $post = \Yii::$app->request->post();
        if (!isset($post['name']) || empty($post['name']))
            return $this->errorResponse("missing parameter name",400);

        $result = WarehousesActions::createUnit($post["name"]);
        if (!$result)
            return $this->errorResponse("Unit created",201);
        else
            return $this->errorResponse("Unit exists",400);
    }

    public function actionWarehouseCreate() {
        $post = \Yii::$app->request->post();
        if (!isset($post['name']) || empty($post['name']))
            return $this->errorResponse("missing parameter name",400);
        if (!isset($post['branch_id']) || empty($post['branch_id']))
            return $this->errorResponse("missing parameter branch_id",400);
        $result = WarehousesActions::WarehouseCreate($post["name"],$post["branch_id"]);
        if (!$result)
             return $this->errorResponse("Warehouse created",201);
        else
            return $this->errorResponse("Warehouse exists",400);

    }

    public function actionWarehouseEdit() {
        $post = \Yii::$app->request->post();
        if (!isset($post['name']) || empty($post['name']))
            return $this->errorResponse("missing parameter name",400);
        if (!isset($post['id']) || empty($post['id']))
            return $this->errorResponse("missing parameter id",400);
        if (!isset($post['branch_id']) || empty($post['branch_id']))
            return $this->errorResponse("missing parameter branch_id",400);
        $result = WarehousesActions::editWarehouse($post["name"],$post["branch_id"],$post["id"]);
        if (!$result)
            return $this->errorResponse("Warehouse Updated",201);
        else
            return $this->errorResponse("Warehouse didn't update",400);

    }


    public function actionProductCreate() {
        $post = \Yii::$app->request->post();
        if (!isset($post['name']) || empty($post['name']))
            return $this->errorResponse("missing parameter name",400);

        if (!isset($post['name']) || empty($post['unit']))
            return $this->errorResponse("missing parameter unit",400);


        $result = WarehousesActions::ProductCreate($post["name"],$post["unit"]);
        if (!$result)
            return $this->errorResponse("Product created",201);
        else
            return $this->errorResponse("Product exists",400);

    }

    public function actionProductsList() {
        $result = WarehousesActions::ProductList();
        return $result;

    }

    public function actionAddSupply() {
        $post = \Yii::$app->request->post();
        if (!isset($post['product_id']) || empty($post['product_id']))
            return $this->errorResponse("missing parameter product_id",400);
        if (!isset($post['warehouse_id']))
            return $this->errorResponse("missing parameter warehouse_id",400);
        if (!isset($post['quantity']) || empty($post['quantity']))
            return $this->errorResponse("missing parameter quantity",400);

        $result = WarehousesActions::addSupplies($post["product_id"],$post["warehouse_id"],$post["quantity"]);
        return $result;
    }

    public function actionSendSupplie() {


        $post = \Yii::$app->request->post();

        if (!isset($post['warehouse_id']) || empty($post['warehouse_id']))
            return $this->errorResponse("missing parameter warehouse_id",400);
        if (!isset($post['supplies']) || empty($post['supplies']))
            return $this->errorResponse("missing parameter supplies",400);

        $result = WarehousesActions::sendSupplies($post['warehouse_id'],$post['supplies']);


        if (!$result)
            return $this->errorResponse("Supplies sent",200);
        else
            return $this->errorResponse("Supplies dont sent",400);
    }

    public function actionSendRequest(){
        $post = \Yii::$app->request->post();

        if (!isset($post['warehouse_id']) || empty($post['warehouse_id']))
            return $this->errorResponse("missing parameter warehouse_id",400);
        if (!isset($post['supplies']) || empty($post['supplies']))
            return $this->errorResponse("missing parameter supplies",400);

        $result = WarehousesActions::requestSupplies($post['warehouse_id'],$post['supplies']);
        if (!$result)
            return $this->errorResponse("Supplies sent",200);
        else
            return $this->errorResponse("Supplies dont sent",400);
    }


    public function actionGetRequestList(){
        $post = \Yii::$app->request->post();
        $result = WarehousesActions::getRequestList($post['warehouse_id'],$post["day"],$post["status"],$post["product_id"]);
       return $result;
    }

    public function actionRequestList(){
        $post = \Yii::$app->request->post();
        if (!isset($post['status']) || empty($post['status']))
            return $this->errorResponse("missing parameter status",400);
        return WarehousesActions::requestList($post["status"]);
    }

    public function actionSentRequests(){
        $post = \Yii::$app->request->post();

        return WarehousesActions::SentRequests($post["warehouse_id"]);
    }

    public function actionReceiveRequests(){
        $post = \Yii::$app->request->post();

        return WarehousesActions::receiveRequests($post["warehouse_id"]);
    }

    public function actionAcceptRequest(){
        $post = \Yii::$app->request->post();
        if (!isset($post['requests']) || empty($post['requests']))
            return $this->errorResponse("missing parameter requests",400);
        $accept =  WarehousesActions::acceptRequets($post["requests"]);

        if ($accept == 1)
            return $this->errorResponse("Faild",400);
        else return "Accepted";
    }

    public function actionSenderUpdateAcceptedRequest(){
        $post = \Yii::$app->request->post();
        if (!isset($post['requests']) || empty($post['requests']))
            return $this->errorResponse("missing parameter requests",400);
        $accept =  WarehousesActions::senderUpdateAcceptedRequest($post["requests"]);

        if ($accept == 1)
            return $this->errorResponse("Faild",400);
        else return "Accepted";
    }

    public function actionSenderCancelAcceptedRequest(){
        $post = \Yii::$app->request->post();
        if (!isset($post['requests']) || empty($post['requests']))
            return $this->errorResponse("missing parameter requests",400);
        $accept =  WarehousesActions::senderCancelAcceptedRequest($post["requests"]);

        if ($accept == 1)
            return $this->errorResponse("Faild",400);
        else return "Accepted";
    }


    public function actionRecieveRequest(){
        $post = \Yii::$app->request->post();
        if (!isset($post['requests']) || empty($post['requests']))
            return $this->errorResponse("missing parameter requests",400);
        $accept =  WarehousesActions::recieveRequets($post["requests"]);

        if ($accept == 1)
            return $this->errorResponse("Faild",400);
        else return $accept;
    }


    public function actionRejectRequest(){
        $post = \Yii::$app->request->post();
        if (!isset($post['requests']) || empty($post['requests']))
            return $this->errorResponse("missing parameter requests",400);
        $accept =  WarehousesActions::rejectRequest($post["requests"]);

        if ($accept == 1)
            return $this->errorResponse("Faild",400);
        else return $accept;
    }

    public function actionVoidRequest(){
        $post = \Yii::$app->request->post();
        if (!isset($post['requests']) || empty($post['requests']))
            return $this->errorResponse("missing parameter requests",400);
        $accept =  WarehousesActions::voidRequets($post["requests"]);

        if ($accept == 1)
            return $this->errorResponse("Faild",400);
        else return $accept;
    }

    public function actionGetTickets() {
        $post = \Yii::$app->request->post();

        if (!isset($post['warehouse_id']) || empty($post['warehouse_id']))
            return $this->errorResponse("missing parameter warehouse_id",400);


        $result = WarehousesActions::getTickets($post['warehouse_id']);
        return $result;
    }


}