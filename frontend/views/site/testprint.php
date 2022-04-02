
<?php

use api\models\database\Customers;
use frontend\assets\SiteAsset;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;


use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use rest\models\response\Response;use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use Automattic\WooCommerce\Client;




$orders = \api\models\database\Orders::find()->where(['<>','driver_id',0])->andWhere(["like", "created_at", "2021-09-%",false])->all();


foreach ($orders as $order) {
    $status = \api\models\database\OrderStatus::find()->where(["id"=>$order["status"]])->one()->status_name;
    $driver = \api\models\database\User::find()->where(["id"=>$order["driver_id"]])->one()->fullname;
    $order_data = Json::decode($order["order_data"]);
    echo $driver . ";";
    echo $order_data["totalPrice"] . ";";
    echo $order_data["discount"]. (strtolower($order_data["discountName"])=="manager"?"":"%").";";
    echo $order["created_at"] . " ; ";
    echo $order_data["deliveryMethod"] . " ; ";
    echo $status.";";
    echo $order["id"] . " <br> ";

}

/*
$ord = \app\models\Orders::find()->where(["id"=>70])->one();

$data  =  Json::decode($ord->order_data);



SiteAsset::register($this);
$in = json_decode(file_get_contents(\Yii::getAlias("@frontend")."/web/ingredients.json"),true);
echo "<pre>";
print_r($in[40][5]["default_topping"]["s"]);
echo "</pre>";


$result = [];

    $ingedients = \api\models\database\Receipt::find()->all();

    foreach ($ingedients as $i) {
        $result[$i->product_id][$i->ingredients_id]["default_topping"] = unserialize($i->default_weight);
        $result[$i->product_id][$i->ingredients_id]["add_topping"] = unserialize($i->topping_wight);

    }
file_put_contents(\Yii::getAlias("@frontend")."/web/ingredients.json", json_encode($result,JSON_UNESCAPED_UNICODE));




        $order_id = 28;



        $orders = \api\models\database\Orders::find()->where(["id"=>$order_id])->all();




            foreach($orders as $ord) {
                $order = Json::decode($ord->order_data);


           if ($ord["source"] == "pos") {





                    foreach ($order["items"] as $prod) {

                        echo ($prod["qty"]." ");
                        if (strtolower($prod["custom"]) == "sticks")
                            echo ($prod["name"]."<br>");
                        else {
                            echo (isset($prod["size"]) ? strtoupper($prod["size"]) : "");
                            echo ($prod["custom"] == "yes"?" A/B <br>":" ".$prod["name"]."<br>");
                        }

                        if (isset($prod["crust"])) {
                            if (strtolower($prod["crust"]) != "original") {
                                echo (" ".$prod["crust"]." crust<br>");
                            }
                        }
                        if (isset($prod["sauce"])) {
                            if (strtolower($prod["sauce"]) != "sauce") {
                                echo (" ".$prod["sauce"]."<br>");
                            }
                        }
                        echo   (isset($prod["cuts"]) && $prod["cuts"]) ?
                             " ".$prod["cutsCount"]." Cuts <br>": "";


                        if (isset($prod["defaultToppings"])) {

                            foreach ($prod["defaultToppings"] as $p_val) {
                                if (isset($p_val["isDeleted"]) && !$p_val["isDeleted"]) {
                                    echo $in[40][$p_val["id"]]["default_topping"][$prod["size"]];
                                    echo ("-".$p_val["name"] ."<br>");
                                }
                            }

                        }

                        if (isset($prod["toppings"])) {
                            foreach ($prod["toppings"] as $p_val) {
                                echo (" +".$p_val["count"] . " " . $p_val["name"] ."<br>");

                            }
                        }
                        if ($prod["custom"] == "yes") {

                            echo ("A. ".$prod["half1"]["name"]."<br>");

                            if (isset($prod["half1"]["defaultToppings"])) {
                                foreach ($prod["half1"]["defaultToppings"] as $h1_d_v) {
                                    if (isset($h1_d_v["isDeleted"]) && $h1_d_v["isDeleted"]) {
                                        echo ($h1_d_v["name"]."<br>");
                                    }



                                }
                            }
                            if (isset($prod["half1"]["toppings"])) {
                                foreach ($prod["half1"]["toppings"] as $h1_d_v) {
                                    echo (" +".$h1_d_v["count"] . " " . $h1_d_v["name"]."<br>");
                                }
                            }
                            echo ("B. ".$prod["half2"]["name"]."<br>");


                            if (isset($prod["half2"]["defaultToppings"])) {
                                foreach ($prod["half2"]["defaultToppings"] as $h1_d_v) {
                                    if (isset($h1_d_v["isDeleted"]) && $h1_d_v["isDeleted"]) {
                                        echo ($h1_d_v["name"]."<br>");
                                    }

//

                                }
                            }
                            if (isset($prod["half2"]["toppings"])) {
                                foreach ($prod["half2"]["toppings"] as $h1_d_v) {
                                    echo (" +".$h1_d_v["count"] . " " . $h1_d_v["name"]."<br>");
                                }
                            }
                        }
                        else {
                            if(isset($prod["half1"]))
                                if(isset($prod["half1"]["toppings"])) {
                                    if (isset($prod["half1"]["toppings"]) && count($prod["half1"]["toppings"]) > 0)
                                        echo ("A. Toppings<br>");
                                    if(isset($prod["half1"]["defaultToppings"]))
                                        foreach ($prod["half1"]["defaultToppings"] as $p_val) {
                                            if (isset($p_val["isDeleted"]) && $p_val["isDeleted"]) {
                                                echo ($p_val["name"]."<br>");

                                            }

                                            //  else echo ($p_val["name"] ."<br>");
                                        }
                                    if(isset($prod["half1"]["toppings"]))
                                        foreach ($prod["half1"]["toppings"] as $p_val) {
                                            echo (" +".$p_val["count"] . " " . $p_val["name"]."<br>");
                                        }
                                }
                            if(isset($prod["half2"]))
                                if(isset($prod["half2"]["toppings"])) {
                                    if (isset($prod["half2"]["toppings"]) && count($prod["half2"]["toppings"]) > 0)
                                        echo ("B Toppings <br>");
                                    if (isset($prod["half2"]["defaultToppings"]))
                                        foreach ($prod["half2"]["defaultToppings"] as $p_val) {
                                            if (isset($p_val["isDeleted"]) && $p_val["isDeleted"]) {
                                                echo ( $p_val["name"]." <br>");
                                            }
//                                            else
//                                                echo ( $p_val["name"]." <br>");
                                        }
                                    if (isset($prod["half2"]["toppings"]))
                                        foreach ($prod["half2"]["toppings"] as $p_val) {
                                            echo ( " +".$p_val["count"] . " " . $p_val["name"]." <br>");
                                        }
                                }



                        }
                        echo ("---------------<br>");
                    }




                }



            }



*/

