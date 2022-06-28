<?php
namespace rest\modules\v1\controllers;

use api\actions\AddressBookAction;
use api\actions\InboxAction;
use api\actions\IpayAction;
use api\actions\OrdersActions;
use api\actions\TasksAction;
use Kerox\Push\Adapter\Fcm;
use Kerox\Push\Push;
use rest\controllers\LangController;
use yii\base\Security;
use yii\imagine\Image;


class IpayController extends LangController {

public function actionGetOrderByShopId() {


    $post = \Yii::$app->request->post();

    if (!isset($post['shop_order_id']) || empty($post['shop_order_id']))
        return $this->errorResponse("missing parameter shop_order_id",400);

    return IpayAction::getOrderByShopOrder($post['shop_order_id']);

}

    public function actionRequestPay() {
        $post = \Yii::$app->request->post();

        if (!isset($post['total_price']) || empty($post['total_price']))
            return $this->errorResponse("missing parameter Total price",400);
        if (!isset($post['items']) || empty($post['items']))
            return $this->errorResponse("missing parameter items",400);
        if (!isset($post['order_data']) || empty($post['order_data']))
            return $this->errorResponse("missing parameter order_data",400);

        return IpayAction::RequestPay($post['total_price'], $post['items'],\Opis\Closure\serialize($post["order_data"]));

    }


    public function actionRefund(){



        $order_id = \Yii::$app->request->post("order_id");
        return IpayAction::refund($order_id);

        //  $amount = \Yii::$app->request->post("amount");




        //    return  $total_price;


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

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ipay.ge/opay/api/v1/checkout/refund',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
  "order_id": "'.$order_id.'"
}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$response->access_token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return  json_decode($response);

    }


    public function actionCheckoutPayment() {
        $post = \Yii::$app->request->post();
        if (!isset($post['order_id']) || empty($post['order_id']))
            return $this->errorResponse("missing parameter order_id",400);

        return IpayAction::CheckoutPayment($post["order_id"]);


    }


    public function actionAccepPayment(){
        $order_id = \Yii::$app->request->post("order_id");

        return IpayAction::AcceptPayment($order_id);

    }

    public function actionCancelPayment(){
        $order_id = \Yii::$app->request->post("order_id");


        return IpayAction::CancelPayment($order_id);

    }

    // get address bookis
    public function actionPayment() {
        $post = \Yii::$app->request->post();


//        IpayAction::callbackPayment($post["order_id"],$post["pre_auth_status"]);

//        \Yii::error("Payment".strtolower($post["pre_auth_status"]));
        return "Done";

    }

    public function actionReverse() {
//        $post = \Yii::$app->request->post();
//
//        IpayAction::callbackPayment($post["order_id"],$post["pre_auth_status"]);
//        \Yii::error("REJECT");
//        \Yii::error($post);

        return "Reject";

    }


}