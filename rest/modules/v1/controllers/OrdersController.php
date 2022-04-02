<?php
namespace rest\modules\v1\controllers;
use api\actions\OrdersActions;
use api\actions\UserAction;
use api\models\database\OrderActions;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Experimental\Unifont\FontMap;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use rest\controllers\RestController;
use rest\models\response\Response;
use yii\helpers\Json;

class OrdersController extends RestController  {

    public function actionList() {


        $day = \Yii::$app->request->post("day")?\Yii::$app->request->post("day"):false;
        $status = \Yii::$app->request->post("status_key");
        $response = new Response();

        if (!$status) {
            $response->error_message = "Missing parameter: 'status_key'";
            return $response;
        }

        $result = OrdersActions::OrdersList($status, $day);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;

    }

    public function actionPrint() {

        $response = new Response();

        $order_id = \Yii::$app->request->post("id");

        if (!$order_id || $order_id<=100 || $order_id == null) {
            $response->error_message = "Missing parameter: 'id'";
            return $response;
        }

        $orders = \api\models\database\Orders::find()->where(["id"=>$order_id])->all();
        try {

            mb_internal_encoding("UTF-8");
            $profile = CapabilityProfile::load("simple");
            $connector = new NetworkPrintConnector("192.168.1.87", 9100);

            /* Print a "Hello world" receipt" */

            $printer = new Printer($connector,$profile);

            foreach($orders as $ord) {
                $order = Json::decode($ord->order_data);



                if ($ord["source"] == "woocommerce") {

                    foreach ($order["line_items"] as $item) {


                        $size = null;

                        if (count($item["meta_data"])) {
                            switch ($item["meta_data"][0]["value"]) {
                                case "small":
                                    $size = "S";
                                    break;
                                case  "medium":
                                    $size = "M";
                                    break;
                                case  "xl":
                                    $size = "XL";
                                    break;
                            }
                        }
                        $printer -> text($item["quantity"] . "   " . $size . "   " . $item["name"]."\n");
                        foreach ($item["meta_data"] as $desc) {
                            if ($desc["value"] == "medium" || $desc["value"] == "small" || $desc["value"] == "xl") {} else {
                                $printer -> setTextSize(2,2);
                                $printer -> text($desc["value"]."\n");
                            }
                        }


                    }
                }

                elseif ($ord["source"] == "pos") {
                    $printer->initialize();
                    $printer->setPrintWidth(380);
                    $printer->setColor(Printer::COLOR_2);
                    $printer->setFont(Printer::FONT_B);

                    $printer->setTextSize(2,2);
                    $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
                    $printer->setPrintLeftMargin(40);
                    $printer -> text("Ronny's Digomi \n");
                    $printer->setPrintLeftMargin(0);

                    $printer -> text("\n");
                    $printer -> text("\n");
                    $printer->setFont(Printer::FONT_A);

                    $printer -> text("order id# ". $ord["id"]."\n");
                    $printer -> text("---------------\n");
                    if (strtolower($order["deliveryMethod"]) == 'glovo' || strtolower($order["deliveryMethod"]) == 'wolt')
                    $printer -> text($order["deliveryMethod"]." ".((strtolower($order["deliveryMethod"]) == 'glovo' && strtolower($order["paymentType"]) != "transfer")?"Cash":"")." ".$order["customer"]["code"]."\n");
                    else
                        $printer -> text($order["deliveryMethod"]."\n");

                    $printer -> text("---------------\n\n");
                    $printer -> text("Created ".date('d-`M', strtotime($ord["created_at"]))."\n");
                    if ($order["isFuture"]) {
                        $printer -> text("Future ".date('d-M', strtotime($order["date"]))."\n");
                    }
                    if (!$order["isFuture"]) {
                        $printer->text("Start ");
                        $printer->text(date('H:i', strtotime($ord["created_at"])) . "\n");
                    }
                    if ($order["isFuture"]) {
                        $printer -> text("Promised ".date('H:i', strtotime($order["date"]))."\n");
                    } else
                        $printer -> text("Promised ".date('H:i', (strtotime($ord["created_at"])+$order["promiseTime"]*60))."\n");
                    $printer -> text("---------------\n\n");

                    if (isset($order["customer"]["name"])) {

                        $printer->text(trim($order["customer"]["name"])."\n");
                        $printer->text($order["customer"]["phone"]."\n");
                        if (is_array($order["customer"]["address"])) {

                            foreach ($order["customer"]["address"] as $cad) {
                                if($cad != "")
                                    $printer->text($cad."\n");
                            }
                        } else
                            $printer->text($order["customer"]["address"]."\n");
                        $printer->text($order["customer"]["comment"]."\n");

                        $printer -> text("---------------\n\n");
                    }
                    foreach ($order["items"] as $prod) {
                        $printer->setTextSize(2,2);
                        $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);

                        $printer->text($prod["qty"]." ");
                        if (strtolower($prod["custom"]) == "sticks")
                            $printer->text($prod["name"]."\n");
                        else {
                            $printer->text(isset($prod["size"]) ? strtoupper($prod["size"]) : "");
                            $printer->text($prod["custom"] == "yes"?" A/B \n":" ".$prod["name"]."\n");
                        }

                        if (isset($prod["crust"])) {
                            if (strtolower($prod["crust"]) != "original") {
                                $printer->setPrintLeftMargin(20);
                                $printer -> text(" ".$prod["crust"]." crust\n");
                                $printer -> setPrintLeftMargin(0);
                            }
                        }
                        if (isset($prod["sauce"])) {
                            if (strtolower($prod["sauce"]) != "sauce") {
                                $printer -> setPrintLeftMargin(20);
                                $printer -> text(" ".$prod["sauce"]."\n");
                                $printer -> setPrintLeftMargin(0);
                            }
                        }
                        $printer -> setPrintLeftMargin(20);
                        (isset($prod["cuts"]) && $prod["cuts"]) ?
                            $printer -> text(" ".$prod["cutsCount"]." Cuts \n"): "";


                        /*  $printer -> selectPrintMode();*/
                        if (isset($prod["defaultToppings"])) {

                            foreach ($prod["defaultToppings"] as $p_val) {
                                if (isset($p_val["isDeleted"]) && $p_val["isDeleted"]) {
                                    $printer -> text("-".$p_val["name"] ."\n");
                                } /*else {
                                    $printer -> text($p_val["name"] ."\n");
                                }*/
                            }

                        }

                        if (isset($prod["toppings"])) {
                            foreach ($prod["toppings"] as $p_val) {
                                $printer -> text(" +".$p_val["count"] . " " . $p_val["name"] ."\n");

                            }
                        }
                        if ($prod["custom"] == "yes") {

                            $printer -> text("A. ".$prod["half1"]["name"]."\n");

                            if (isset($prod["half1"]["defaultToppings"])) {
                                foreach ($prod["half1"]["defaultToppings"] as $h1_d_v) {
                                    if (isset($h1_d_v["isDeleted"]) && $h1_d_v["isDeleted"]) {
                                        $printer -> setPrintLeftMargin(20);
                                        $printer -> text($h1_d_v["name"]."\n");
                                        $printer -> setPrintLeftMargin(0);
                                    }

                                    /* else
                                         $printer -> text($h1_d_v["name"]."\n");*/

                                }
                            }
                            if (isset($prod["half1"]["toppings"])) {
                                foreach ($prod["half1"]["toppings"] as $h1_d_v) {
                                    $printer -> setPrintLeftMargin(20);
                                    $printer -> text(" +".$h1_d_v["count"] . " " . $h1_d_v["name"]."\n");
                                    $printer -> setPrintLeftMargin(0);
                                }
                            }
                            $printer -> text("B. ".$prod["half2"]["name"]."\n");


                            if (isset($prod["half2"]["defaultToppings"])) {
                                foreach ($prod["half2"]["defaultToppings"] as $h1_d_v) {
                                    if (isset($h1_d_v["isDeleted"]) && $h1_d_v["isDeleted"]) {
                                        $printer -> setPrintLeftMargin(20);
                                        $printer -> text($h1_d_v["name"]."\n");
                                        $printer -> setPrintLeftMargin(0);
                                    }

//                                    else
//                                        $printer -> text($h1_d_v["name"]."\n");

                                }
                            }
                            if (isset($prod["half2"]["toppings"])) {
                                foreach ($prod["half2"]["toppings"] as $h1_d_v) {
                                    $printer -> setPrintLeftMargin(20);
                                    $printer -> text(" +".$h1_d_v["count"] . " " . $h1_d_v["name"]."\n");
                                    $printer -> setPrintLeftMargin(0);
                                }
                            }
                        }
                        else {
                            if(isset($prod["half1"]))
                                if(isset($prod["half1"]["toppings"])) {
                                    if (isset($prod["half1"]["toppings"]) && count($prod["half1"]["toppings"]) > 0)
                                        $printer -> text("A. Toppings\n");
                                    if(isset($prod["half1"]["defaultToppings"]))
                                        foreach ($prod["half1"]["defaultToppings"] as $p_val) {
                                            if (isset($p_val["isDeleted"]) && $p_val["isDeleted"]) {
                                                $printer -> setPrintLeftMargin(20);
                                                $printer -> text($p_val["name"]."\n");
                                                $printer -> setPrintLeftMargin(0);

                                            }

                                            //  else $printer -> text($p_val["name"] ."\n");
                                        }
                                    if(isset($prod["half1"]["toppings"]))
                                        foreach ($prod["half1"]["toppings"] as $p_val) {
                                            $printer -> setPrintLeftMargin(20);
                                            $printer -> text(" +".$p_val["count"] . " " . $p_val["name"]."\n");
                                            $printer -> setPrintLeftMargin(0);
                                        }
                                }
                            if(isset($prod["half2"]))
                                if(isset($prod["half2"]["toppings"])) {
                                    if (isset($prod["half2"]["toppings"]) && count($prod["half2"]["toppings"]) > 0)
                                        $printer -> text("B Toppings \n");
                                    if (isset($prod["half2"]["defaultToppings"]))
                                        foreach ($prod["half2"]["defaultToppings"] as $p_val) {
                                            if (isset($p_val["isDeleted"]) && $p_val["isDeleted"]) {
                                                $printer -> setPrintLeftMargin(20);
                                                $printer -> text( $p_val["name"]." \n");
                                                $printer -> setPrintLeftMargin(0);
                                            }
//                                            else
//                                                $printer -> text( $p_val["name"]." \n");
                                        }
                                    if (isset($prod["half2"]["toppings"]))
                                        foreach ($prod["half2"]["toppings"] as $p_val) {
                                            $printer -> setPrintLeftMargin(20);
                                            $printer -> text( " +".$p_val["count"] . " " . $p_val["name"]." \n");
                                            $printer -> setPrintLeftMargin(0);
                                        }
                                }



                        }
                        $printer -> text("---------------\n");
                    }


                    $printer -> text("\n");
                    $printer -> text("\n");
                    if($order["deliveryFee"] > 0) {
                        $printer -> text("---------------\n\n");
                        $printer -> text("Delivery Fee: ".$order["deliveryFee"]."\n");
                    }

                    $printer -> text("---------------\n");
                    $printer -> text("Total: ");
                    $printer -> text($order["totalPrice"]."\n");



                    if(isset($order["discount"]) && $order["discount"]>0) {
                        $printer -> text("discount: ");
                        if($order["discountName"]=="Diplomat") {
                            $printer -> text(number_format(($order["totalPrice"]-$order["totalPrice"]/1.18),2)."\n");
                            $printer -> text("Total: ");
                            $printer -> text(number_format(($order["totalPrice"]/1.18),2)."\n");
                        } else if ($order["discountAmount"]) {
                            $printer -> text(number_format($order["discount"],2)."\n");
                            $printer -> text("Total: ");
                            $printer -> text(number_format(($order["totalPrice"]-$order["discount"]),2)."\n");
                        } else {


                            $printer -> text(number_format(($order["totalPrice"]*$order["discount"]/100),2)."\n");
                            $printer -> text("Total: ");
                            $printer -> text(number_format(($order["totalPrice"]-$order["totalPrice"]*$order["discount"]/100),2)."\n");
                        }
                    }


                    $result  = "Printed";
                }


                elseif($ord["source"] == "Legacy") {
                    foreach($order[0]["items"]as $prod) {
                        $printer -> text( $prod["quantity"]."X ".$prod["name"]." \n");
                        $printer -> text( $prod["instructions"]." \n");

                        foreach($prod["options"]as $p_val) {
                            $printer -> text( $p_val["group_name"].": ".$p_val["name"]." \n");

                        }
                    }
                }
            }
            $printer -> text("\n\n\n\n\n\n\n");

            $printer -> cut();

            /* Close printer */
            $printer -> close();
        } catch (Exception $e) {
            $result =  "Couldn't print to this printer: " . $e -> getMessage() . "\n";
        }

        $response->is_error =  !$result;
        $response->error_message = !$result ? 'Operation failed' : '';
        $response->data = $result;
        return $response;
    }

    public function actionCreate() {

        $response = new Response();

        $order =  \Yii::$app->request->post('order');

        if (!$order) {
            $response->error_message = "Missing parameter: 'order'";
            return $response;
        }
        $result = OrdersActions::createOrder(\Opis\Closure\serialize($order),"pos");
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionCreateForWeb() {

        $response = new Response();

        $order =  \Yii::$app->request->post('order');

        if (!$order) {
            $response->error_message = "Missing parameter: 'order'";
            return $response;
        }
        $result = OrdersActions::createOrderForWeb(\Opis\Closure\serialize($order),"web");
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionWaste() {

        $response = new Response();

        $order =  \Yii::$app->request->post('order');

        if (!$order) {
            $response->error_message = "Missing parameter: 'order'";
            return $response;
        }
        $result = OrdersActions::createWaste(\Opis\Closure\serialize($order),"pos");
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionChangeStatus() {


        $response = new Response();

        $id =  \Yii::$app->request->post('id');
        $status =  \Yii::$app->request->post('order_status');
        if (strtolower($status) == "refund" || strtolower($status) == "void") {
            $response->error_message = "Service isn't avialable";
            return $response;
        }
        if (!$id) {
            $response->error_message = "Missing parameter: 'id'";
            return $response;
        }
        if (!$status) {
            $response->error_message = "Missing parameter: 'status'";
            return $response;
        }
        $result = OrdersActions::changeStatus($id, $status);
        $response->is_error =  $result > 0 ? false: true;
        $response->error_message = $result > 0 ? 'No data' : $result;
        $response->data = $result;
        return $response;
    }

    public function actionReopen() {

        $response = new Response();

        $order =  \Yii::$app->request->post('order');

        if (!$order) {
            $response->error_message = "Missing parameter: 'order'";
            return $response;
        }
        $result = OrdersActions::reopen(\Opis\Closure\serialize($order));
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionEdit() {

        $response = new Response();

        $order =  \Yii::$app->request->post('order');

        if (!$order) {
            $response->error_message = "Missing parameter: 'order'";
            return $response;
        }
        $result = OrdersActions::editOrder(\Opis\Closure\serialize($order));
        $response->is_error =  $result > 0 ? false: true;
        $response->error_message = $result > 0 ? 'No data' : $result;
        $response->data = $result;
        return $response;
    }

    public function actionEditOrderForDriver(){

        $response = new Response();
        $id =  \Yii::$app->request->post('order_id');
        $driver_id =  \Yii::$app->request->post('driver_id');
        $tips =  \Yii::$app->request->post('tips');
        $payment_method =  \Yii::$app->request->post('payment_method');
        $split_cash =  \Yii::$app->request->post('split_cash');
        $split_card =  \Yii::$app->request->post('split_card');

        if (!$id) {
            $response->error_message = "Missing parameter: 'order_id'";
            return $response;
        }
        if (!$driver_id) {
            $response->error_message = "Missing parameter: 'driver_id'";
            return $response;
        }
        if (!$payment_method) {
            $response->error_message = "Missing parameter: 'payment_method'";
            return $response;
        }
        if (!$split_cash) {
            $response->error_message = "Missing parameter: 'split_cash'";
            return $response;
        }
        if (!$split_card) {
            $response->error_message = "Missing parameter: 'split_card'";
            return $response;
        }

        $result = OrdersActions::EditOrderForDriver($id, $driver_id,$payment_method, $split_cash, $split_card,$tips);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;

    }

    public function actionRefund() {

        $response = new Response();

        $id =  \Yii::$app->request->post('id');
        $pos_id =  \Yii::$app->request->post('pos_id');


        if (!$pos_id) {
            $response->error_message = "Missing parameter: 'pos_id'";
            return $response;
        }

        $result = OrdersActions::refund($id, $pos_id);
        $response->is_error =  $result > 0 ? false: true;
        $response->error_message = $result > 0 ? 'No data' : $result;
        $response->data = $result;
        return $response;
    }

    public function actionVoid() {

        $response = new Response();

        $id =  \Yii::$app->request->post('id');
        $pos_id =  \Yii::$app->request->post('pos_id');


        if (!$id) {
            $response->error_message = "Missing parameter: 'id'";
            return $response;
        }

        if (!$pos_id) {
            $response->error_message = "Missing parameter: 'pos_id'";
            return $response;
        }

        $result = OrdersActions::void($id, $pos_id);
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }

    public function actionPaid() {
        $response = new Response();

        $order =  \Yii::$app->request->post('order');

        if (!$order) {
            $response->error_message = "Missing parameter: 'Order'";
            return $response;
        }

        $result = OrdersActions::paid(\Opis\Closure\serialize($order));
        $response->is_error =  !$result;
        $response->error_message = !$result ? 'No data' : '';
        $response->data = $result;
        return $response;
    }







}