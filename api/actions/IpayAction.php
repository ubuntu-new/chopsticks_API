<?php
namespace api\actions;

use api\models\database\Orders;
use yii\base\Exception;
use yii\helpers\Json;
use api\models\response\Result;



class IpayAction {

    public static function getOrderByShopOrder($shop_order_id) {

        return Orders::find()->where(["shop_order_id"=>$shop_order_id])->one();

    }

    public static function BogAuth()
    {
        $header =  base64_encode("14962:8b8316fc205ed2d70199cd69ba315b18");

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ipay.ge/opay/api/v1/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic '.$header,
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = json_decode(curl_exec($curl));

        return  $response->access_token;
    }

    public static function RequestPay($items = null, $customer = null, $lang = null, $cutlery=null, $user_id=null, $payment_method = null, $tottalprice = null ){


        $result = OrdersActions::OrdersCreate(Json::encode(unserialize($items)), Json::encode($customer), Json::encode($cutlery), $lang, $user_id, $tottalprice, $payment_method);
        $item = unserialize($items);



        if($result>1) {

            $shop_order_id = \Yii::$app->security->generateRandomString(16);

            $curl = curl_init();


            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://ipay.ge/opay/api/v1/checkout/orders',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                  "intent": "CAPTURE",
                  "items": [],
                  "locale": "ka",
                  "shop_order_id": "'.$shop_order_id.'",
                   "redirect_url": "https://chopsticks.com.ge/payment-response?id='.$shop_order_id.'",
                  "show_shop_order_id_on_extract": true,
                  "capture_method": "AUTOMATIC",
                  "purchase_units": [
                    {
                      "amount": {
                        "currency_code": "GEL",
                        "value": "1"
                      }
                    }
                  ]
                }',
            CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.self::BogAuth(),
                    'Content-Type: application/json'
                ),
            ));

            $response = json_decode(curl_exec($curl));



            curl_close($curl);


            $orderasd = Orders::find()->where(["id"=>$result])->one();

            $orderasd->opay_status = "in_progress";
            $orderasd->opay_order_id = $response->order_id;
            $orderasd->shop_order_id = $shop_order_id;
            $orderasd->save();

            return  $response;
        } else return Result::FAILURE;
    }

    public static function AcceptPayment($order_id = null ){

        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ipay.ge/opay/api/v1/checkout/payment/'.$order_id.'/pre-auth/completion',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{"auth_type": "FULL_COMPLETE"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.self::BogAuth(),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return  json_decode($response);

    }

    public static function  CancelPayment($order_id = null ){

        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ipay.ge/opay/api/v1/checkout/payment/'.$order_id.'/pre-auth/completion',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"auth_type" : "CANCEL"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.self::BogAuth(),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return  json_decode($response);

    }

    public static function  refund($order_id = null ){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ipay.ge/opay/api/v1/checkout/refund',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'order_id='.$order_id,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.self::BogAuth(),
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return  json_decode($response);

    }

    public static function  CheckoutPayment($order_id = null ){

        $curl = curl_init();


        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ipay.ge/opay/api/v1/checkout/payment/'.$order_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',

            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.self::BogAuth(),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return  json_decode($response);

    }


    public static function callbackPayment($order_id = null, $status = null) {


    //    \Yii::error("asdasd");

        $order = Orders::find()->where(["opay_order_id"=>$order_id])->one();
        if($order) {
            $order->opay_status = strtolower($status);
            $order->save();
        }




    }




}