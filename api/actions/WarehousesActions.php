<?php
/**
 * Created by PhpStorm.
 * User: levan
 * Date: 4/13/2020
 * Time: 12:42
 */

namespace api\actions;


use api\models\database\Status;
use api\models\database\Warehouses;
use api\models\database\Wproducts;
use api\models\database\WRequsetStatus;
use api\models\database\WSupplies;
use api\models\database\WSuppliesDetail;
use api\models\database\WSuppliesRequest;
use api\models\database\WTicket;
use api\models\database\Wunit;
use api\models\response\Result;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Yii;
use yii\db\Exception;

use yii\db\mssql\PDO;

class WarehousesActions
{
    public static function SuppliesList($warehouse_id = null) {
        if ($warehouse_id == null)
            $supplies =  WSupplies::find()->all();
        else  $supplies =  WSupplies::find()->andWhere(["warehouse_id"=>$warehouse_id])->all();
        return $supplies;
    }


    public static function WarehouseList() {

        $sql = " SELECT * FROM warehouses w 
                left join statuses s ON s.id = w.status ";
        $rows = Yii::$app->dbw->createCommand($sql)->queryAll(PDO::FETCH_OBJ);
        return $rows;
    }

    public static function WarehouseUnit() {

        return Wunit::find()->where(["status"=>Status::getActive()])->all();

    }

    public static function WarehouseCreate($name=null, $branch_id = null) {

        $warehouse =  Warehouses::find()->where(["branch_id"=>$branch_id])->one();
        if ($warehouse)
            return Result::FAILURE;
        else {

            $transaction = Yii::$app->dbw->beginTransaction();

            try {
                $warehouse = new Warehouses();
                $warehouse->branch_id = $branch_id;
                $warehouse->name = $name;
                $warehouse->save();



                $transaction->commit();

                return Result::SUCCESS;


            } catch (Exception $ex) {
                $transaction->rollBack();
                Yii::error($ex->getMessage());
            }
        }
        return Result::FAILURE;
    }

    public static function createUnit($name=null) {

        $warehouse =  Wunit::find()->where(["name"=>$name])->one();
        if ($warehouse)
            return Result::FAILURE;
        else {

            $transaction = Yii::$app->dbw->beginTransaction();

            try {
                $warehouse = new Wunit();
                $warehouse->name = $name;
                $warehouse->status = Status::getActive();
                $warehouse->save();



                $transaction->commit();

                return Result::SUCCESS;


            } catch (Exception $ex) {
                $transaction->rollBack();
                \Yii::error($ex->getMessage());
            }
        }
        return Result::FAILURE;
    }

    public static function ProductList() {

        return Wproducts::find()->all();

    }

    public static function ProductCreate($name=null, $unit = null) {

        $product =  Wproducts::find()->where(["name"=>$name])->one();
        if ($product)
            return Result::FAILURE;
        else {

            $transaction = \Yii::$app->dbw->beginTransaction();

            try {
                $product = new Wproducts();
                $product->unit = $unit;
                $product->name = $name;
                $product->save();

                $transaction->commit();

                return Result::SUCCESS;


            } catch (Exception $ex) {
                $transaction->rollBack();
                \Yii::error($ex->getMessage());
            }
        }
        return Result::FAILURE;
    }

    public static function addSupplies($product_id=null, $warehouse_id = null, $quantity = null , $minQty = null) {

        $product =  Wproducts::find()->where(["id"=>$product_id])->one();
        $suplie = WSupplies::find()->where(["product_id"=>$product_id])->andwhere(["warehouse_id"=>$warehouse_id])->one();
        $warehouse = Warehouses::find()->where(["id"=>$warehouse_id])->one();



        $transaction = Yii::$app->dbw->beginTransaction();

        try {

            if (!$suplie) {
                $suplie = new WSupplies();
                $suplie->product_id = $product_id;
                $suplie->product_name = $product->name;
                $suplie->warehouse_id = $warehouse_id;
                $suplie->quantity = $quantity;
                $suplie->unit = $product->unit;
                $suplie->save();
            } else {
                $suplie->product_name = $product->name;
                $suplie->quantity += $quantity;
                $suplie->save();
            }

            $supplies_detail = new WSuppliesDetail();
            $supplies_detail->from_warehouse_id = $warehouse->id;
            $supplies_detail->from_warehouse_name = $warehouse->name;
            $supplies_detail->to_warehouse_id = $warehouse->id;
            $supplies_detail->to_warehouse_name = $warehouse->name;
            $supplies_detail->supplie_id = $suplie->id;
            $supplies_detail->quantity = $quantity;
            $supplies_detail->action = "Add supplie";
            $supplies_detail->product_id = $product_id;
            $supplies_detail->product_name = $product->name;
            $supplies_detail->save();

            $transaction->commit();

            return "Supplie add successfully";


        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }

        return "Something went wrong";
    }


    public static function sendSupplies($warehouse_id = null, $supplies = null ) {


        $warehouse =  Warehouses::find()->where(["id"=>$warehouse_id])->one();
        $local_warehouse = Warehouses::find()->where(["branch_id"=>Yii::$app->user->identity->branch_id])->one();

        $transaction = \Yii::$app->dbw->beginTransaction();

        try {

            foreach ($supplies as $sup) {

                $product = Wproducts::find()->where(["id"=>$sup["product_id"]])->one();

                $request = new WSuppliesRequest();
                $request->product_id = $sup["product_id"];
                $request->status = WRequsetStatus::getAccept();
                $request->product_name = $product->name;
                $request->from_warehouse_id = $local_warehouse->id;
                $request->from_warehouse_name = $local_warehouse->name;
                $request->to_warehouse_id = $warehouse->id;
                $request->to_warehouse_name= $warehouse->name;
                $request->user_id= Yii::$app->user->getId();
                $request->sent_quantity = $sup["quantity"];
                $request->unit = $product->unit;
                $request->save();

                $suplie = WSupplies::find()->where(["product_id"=>$sup["product_id"]])->andWhere(["warehouse_id"=>$local_warehouse->id])->one();
                $suplie->quantity = $suplie->quantity-$sup["quantity"];
                $suplie->save();

                $supplies_detail = new WSuppliesDetail();
                $supplies_detail->request_id = $request->id;
                $supplies_detail->to_warehouse_id = $request->to_warehouse_id;
                $supplies_detail->to_warehouse_name = $request->to_warehouse_name;
                $supplies_detail->from_warehouse_id = $request->from_warehouse_id;
                $supplies_detail->from_warehouse_name = $request->from_warehouse_name;
                $supplies_detail->user_id = Yii::$app->user->getId();
                $supplies_detail->supplie_id = $suplie->id;
                $supplies_detail->quantity = $sup["quantity"];
                $supplies_detail->action = "Send supplie";
                $supplies_detail->product_id = $request->product_id;
                $supplies_detail->product_name = $request->product_name;
                $supplies_detail->save();

            }


            $transaction->commit();

            return Result::SUCCESS;


        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }

        return Result::FAILURE;
    }

    public static function requestSupplies($warehouse_id = null, $supplies = null ) {

        $warehouse =  Warehouses::find()->where(["id"=>$warehouse_id])->one();
        $local_warehouse = Warehouses::find()->where(["branch_id"=>Yii::$app->user->identity->branch_id])->one();

        $transaction = \Yii::$app->dbw->beginTransaction();

        try {

            foreach ($supplies as $sup) {
                $product = Wproducts::find()->where(["id"=>$sup["product_id"]])->one();

                $request = new WSuppliesRequest();
                $request->product_id = $sup["product_id"];
                $request->product_name = $product->name;
                $request->from_warehouse_id = $local_warehouse->id;
                $request->from_warehouse_name = $local_warehouse->name;
                $request->to_warehouse_id = $warehouse->id;
                $request->to_warehouse_name= $warehouse->name;
                $request->user_id= Yii::$app->user->getId();
                $request->quantity = $sup["quantity"];
                $request->unit = $product->unit;
                $request->save();
            }

            $ServiceAccount = ServiceAccount::fromJsonFile(\Yii::getAlias("@anyname")."/testphp-d3f2c-firebase-adminsdk-n206t-a5b65789a2.json");
            $firebase = (new Factory)->
            withServiceAccount($ServiceAccount)->
            withDatabaseUri('https://testphp-d3f2c-default-rtdb.firebaseio.com')->create();

            $data = [
                'Branch'=>$warehouse->name,
                'Order_id'=>$request->id,
            ];

            $ref = '/requests';

            $database = $firebase->getDatabase();
            $postdata = $database->getReference('/requests')->push($data);

            $transaction->commit();

            return Result::SUCCESS;


        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }

        return Result::FAILURE;
    }

    public static function requestList( $status = null) {

        $sql = "SELECT sum(quantity) sum, s.* FROM `request_supplies` s where s.status = :status GROUP by s.product_id";
        $rows = \Yii::$app->dbw->createCommand($sql)
            ->bindValue(":status", $status)
            ->queryAll(PDO::FETCH_OBJ);
        return $rows;
    }


    public static function sentRequests( $warehouse_id = null) {

        $sql = "SELECT sum(quantity) sum,  CONCAT(quantity, ' ', unit) as quanunit, s.* FROM `request_supplies` s 
                where s.from_warehouse_id = :from_warehouse_id and s.status IN (1,2) GROUP by s.product_id";
        $rows = \Yii::$app->dbw->createCommand($sql)
            ->bindValue(":from_warehouse_id", $warehouse_id)

            ->queryAll(PDO::FETCH_OBJ);
        return $rows;
    }

    public static function receiveRequests( $warehouse_id = null) {

        $sql = "SELECT sum(quantity) sum,  CONCAT(quantity, ' ', unit) as quanunit, s.* FROM `request_supplies` s 
                where s.to_warehouse_id = :to_warehouse_id and s.status IN (1,2) GROUP by s.product_id";
        $rows = \Yii::$app->dbw->createCommand($sql)
            ->bindValue(":to_warehouse_id", $warehouse_id)

            ->queryAll(PDO::FETCH_OBJ);
        return $rows;
    }


    public static function getRequestList($warehouse_id = null, $day = null,  $status = null, $product_id = null) {

        $sql = "SELECT sum(quantity) sum, s.* FROM `request_supplies` s where s.created_at >= :start_day and s.created_at <= :end_day";
        if ($day)
            $day = explode("to",$day);
        else $day = [date("Y-m-d")." 00:00:00",date("Y-m-d")." 23:59:59"];

        if ($warehouse_id)
            $sql .= " AND s.warehouse_id = :warehouse_id";
        if ($status)
            $sql .= " AND s.status = :status";
        if ($product_id)
            $sql .= " AND s.product_id = :product_id";

        $sql .= " GROUP by s.product_id ";

        $rows = \Yii::$app->dbw->createCommand($sql)
            ->bindValue(":start_day", strtotime($day[0]))
            ->bindValue(":end_day", strtotime($day[1]));

        if ($warehouse_id)
            $rows->bindValue(":warehouse_id", $warehouse_id);
        if ($status)
            $rows->bindValue(":status", $status);
        if ($product_id)
            $rows->bindValue(":product_id", $product_id);

        $rows = $rows->queryAll(PDO::FETCH_OBJ);
        return $rows;
    }


    public static function acceptRequets($list = null) {

        $transaction = \Yii::$app->dbw->beginTransaction();
        $result = Result::FAILURE;
        try {
            foreach ($list as $req) {


                $request = WSuppliesRequest::find()->where(["id"=>$req["id"]])->one();

                if ($request->status == WRequsetStatus::getPending()) {
                    $request->status = WRequsetStatus::getAccept();
                    $request->sent_quantity = $req["quantity"];
                    $request->main_w_action_date = date("Y-m-d H:i:s");
                    $request->save();


                    $suplie = WSupplies::find()->where(["product_id"=>$request->product_id])->andWhere(["warehouse_id"=>$request->to_warehouse_id])->one();
                    $suplie->quantity = $suplie->quantity-$req["quantity"];
                    $suplie->save();

                    if($suplie->save())
                        $result = Result::SUCCESS;


                    $supplies_detail = new WSuppliesDetail();
                    $supplies_detail->request_id = $req["id"];
                    $supplies_detail->to_warehouse_id = $request->to_warehouse_id;
                    $supplies_detail->to_warehouse_name = $request->to_warehouse_name;
                    $supplies_detail->from_warehouse_id = $request->from_warehouse_id;
                    $supplies_detail->from_warehouse_name = $request->from_warehouse_name;
                    $supplies_detail->user_id = Yii::$app->user->getId();
                    $supplies_detail->supplie_id = $suplie->id;
                    $supplies_detail->quantity = $req["quantity"];
                    $supplies_detail->action = "Send supplie";
                    $supplies_detail->product_id = $request->product_id;
                    $supplies_detail->product_name = $request->product_name;
                    $supplies_detail->save();

                }
            }
            $transaction->commit();
            return $result;
        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }


    public static function senderUpdateAcceptedRequest($list) {

        $transaction = \Yii::$app->dbw->beginTransaction();

        try {
            foreach ($list as $req) {


                $request = WSuppliesRequest::find()->where(["id"=>$req["id"]])->one();

                if ($request->status == WRequsetStatus::getAccept()) {


                    $suplie = WSupplies::find()->where(["product_id"=>$request->product_id])->andWhere(["warehouse_id"=>$request->to_warehouse_id])->one();
                    $suplie->quantity = $suplie->quantity+$request["sent_quantity"]-$req["quantity"];
                    $suplie->save();

                    $request->sent_quantity = $req["quantity"];
                    $request->save();


                    $supplies_detail = new WSuppliesDetail();

                    $supplies_detail->to_warehouse_id = $request->to_warehouse_id;
                    $supplies_detail->to_warehouse_name = $request->to_warehouse_name;
                    $supplies_detail->from_warehouse_id = $request->from_warehouse_id;
                    $supplies_detail->from_warehouse_name = $request->from_warehouse_name;
                    $supplies_detail->user_id = Yii::$app->user->getId();
                    $supplies_detail->request_id = $req["id"];
                    $supplies_detail->supplie_id = $suplie->id;
                    $supplies_detail->quantity = $req["quantity"];
                    $supplies_detail->action = "Update Send supplie";
                    $supplies_detail->product_id = $request->product_id;
                    $supplies_detail->product_name = $request->product_name;
                    $supplies_detail->save();

                }
            }
            $transaction->commit();
            return Result::SUCCESS;
        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;
    }

    public static function senderCancelAcceptedRequest($list) {



        try {
            $transaction = \Yii::$app->dbw->beginTransaction();$transaction = \Yii::$app->dbw->beginTransaction();
            foreach ($list as $req) {


                $request = WSuppliesRequest::find()->where(["id"=>$req["id"]])->one();

                if ($request->status == WRequsetStatus::getAccept()) {


                    $suplie = WSupplies::find()->where(["product_id"=>$request->product_id])->andWhere(["warehouse_id"=>$request->to_warehouse_id])->one();
                    $suplie->quantity = $suplie->quantity+$request["sent_quantity"];
                    $suplie->save();

                    $supplies_detail = new WSuppliesDetail();
                    $supplies_detail->request_id = $req["id"];
                    $supplies_detail->to_warehouse_id = $request->to_warehouse_id;
                    $supplies_detail->to_warehouse_name = $request->to_warehouse_name;
                    $supplies_detail->from_warehouse_id = $request->from_warehouse_id;
                    $supplies_detail->from_warehouse_name = $request->from_warehouse_name;
                    $supplies_detail->user_id = Yii::$app->user->getId();
                    $supplies_detail->supplie_id = $suplie->id;
                    $supplies_detail->quantity = $request["sent_quantity"];
                    $supplies_detail->action = "Cancel accepted requets";
                    $supplies_detail->product_id = $request->product_id;
                    $supplies_detail->product_name = $request->product_name;
                    $supplies_detail->save();

                    $request->status = WRequsetStatus::getPending();
                    $request->sent_quantity = 0;
                    $request->save();

                }
            }
            $transaction->commit();
            return Result::SUCCESS;
        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;
    }

    public static function recieveRequets($list = null) {

        $transaction = \Yii::$app->dbw->beginTransaction();

        try {
            foreach ($list as $request) {

                $request_data = WSuppliesRequest::find()->where(["id"=>$request["id"]])->one();


                if ($request_data->status == WRequsetStatus::getAccept()) {


                    $suplie_add = WSupplies::find()->where(["product_id"=>$request_data->product_id])
                        ->andWhere(["warehouse_id"=>$request_data->from_warehouse_id])->one();
                    if ($suplie_add) {
                        $suplie_add->quantity = $suplie_add->quantity+$request["quantity"];
                    } else {
                        $suplie_add = new WSupplies();
                        $suplie_add->quantity = $request["quantity"];
                        $suplie_add->warehouse_id = $request_data->from_warehouse_id;
                        $suplie_add->product_id = $request_data->product_id;
                        $suplie_add->product_name = $request_data->product_name;
                        $suplie_add->unit = $request_data->unit;
                    }
                    $suplie_add->save();

                    $request_data->status = WRequsetStatus::getRecieve();
                    $request_data->recieve_quantity = $request["quantity"];
                    $request_data->w_action_date = date("Y-m-d H:i:s");
                    $request_data->save();

                    if (($request_data->sent_quantity-$request["quantity"]) != 0) {
                        $ticket = new WTicket();
                        $ticket->request_id = $request["id"];
                        $ticket->from_warehouse_id = $request_data->from_warehouse_id;
                        $ticket->from_warehouse_name = $request_data->from_warehouse_name;
                        $ticket->to_warehouse_id = $request_data->to_warehouse_id;
                        $ticket->to_warehouse_name = $request_data->to_warehouse_name;
                        $ticket->sent_quantity = $request_data->sent_quantity;
                        $ticket->recieved_quantity = $request["quantity"];
                        $ticket->product_id = $request_data->product_id;
                        $ticket->product_name = $request_data->product_name;
                        $ticket->product_unit = $request_data->unit;
                        $ticket->save();
                    }


                    $supplies_detail = new WSuppliesDetail();

                    $supplies_detail->to_warehouse_id = $request_data->to_warehouse_id;
                    $supplies_detail->to_warehouse_name = $request_data->to_warehouse_name;
                    $supplies_detail->from_warehouse_id = $request_data->from_warehouse_id;
                    $supplies_detail->from_warehouse_name = $request_data->from_warehouse_name;
                    $supplies_detail->user_id = Yii::$app->user->getId();
                    $supplies_detail->request_id = $request["id"];
                    $supplies_detail->supplie_id = $suplie_add->id;
                    $supplies_detail->quantity = $request["quantity"];
                    $supplies_detail->action = "Reciever accepted requets";
                    $supplies_detail->product_id = $request_data->product_id;
                    $supplies_detail->product_name = $request_data->product_name;
                    $supplies_detail->save();
                }
            }
            $transaction->commit();
            return Result::SUCCESS;
        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function rejectRequets($list = null) {

        $transaction = \Yii::$app->dbw->beginTransaction();

        try {
            foreach ($list as $request) {
                $request = WSuppliesRequest::find()->where(["id"=>$request["id"]])->one();
                if ($request->status == 1) {
                    $request->status = WRequsetStatus::getReject();
                    $request->recieve_quantity = $request["quantity"];
                    $request->w_action_date = date("Y-m-d H:i:s");
                    $request->save();
                }
            }
            $transaction->commit();
            return Result::SUCCESS;
        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

    public static function voidRequets($list = null) {

        $transaction = \Yii::$app->dbw->beginTransaction();

        try {
            foreach ($list as $request) {
                $request = WSuppliesRequest::find()->where(["id"=>$request["id"]])->one();
                if ($request->status == 1) {
                    $request->status = WRequsetStatus::getVoid();
                    $request->w_action_date = date("Y-m-d H:i:s");
                    $request->save();
                }
            }
            $transaction->commit();
            return Result::SUCCESS;
        } catch (Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;

    }

}