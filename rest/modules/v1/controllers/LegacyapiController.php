<?php
namespace rest\modules\v1\controllers;

use api\actions\UserAction;
use api\models\database\IngredientsPrice;
use api\models\database\Orders;
use api\models\response\Result;
use rest\controllers\RestController;
use yii\base\Security;
use rest\models\response\Response;
use yii\helpers\Json;

class LegacyapiController extends RestController  {

    function removeEmoji($text)
    {
        $cleanText = "";

        // Match Emoticons
        $regexEmoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $cleanText = preg_replace($regexEmoticons, '', $text);

        // Match Miscellaneous Symbols and Pictographs
        $regexSymbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $cleanText = preg_replace($regexSymbols, '', $cleanText);

        // Match Transport And Map Symbols
        $regexTransport = '/[\x{1F680}-\x{1F6FF}]/u';
        $cleanText = preg_replace($regexTransport, '', $cleanText);

        return $cleanText;
    }



    public function actionCreateOrder() {
    //    $order =  \Opis\Closure\serialize(\Yii::$app->request->post('orders'));
        $request = \Yii::$app->request->post("orders");

// returns all parameters
//        $params = $request->bodyParams;
        $clean_text = "";



        $order_data = $request;
        $order_id =  $order_data[0]["id"];
        switch($order_data[0]["status"]){
            case "pending":
                $status = 0;
                break;
            case "accepted":
                $status = 1;
                break;
            case "rejected":
                $status = 5;
                break;
            case "missed":
                $status =5;
                break;
            default:
                $status = 0;
        }

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $order = Orders::find()->where(["order_id"=>$order_id])->one();
            if ($order) {
                if ($status == 1)
                {
                    $start_date = strtotime($order_data[0]["accepted_at"]);
                    $end_date = strtotime($order_data[0]["fulfill_at"]);
                    $order->duration = ($end_date - $start_date)/60;
                }

                $order->status = $status;
                $order->order_data =  self::removeEmoji(Json::encode($order_data));
            }
            else {
                $order = new Orders();
                $order->order_data	 =  self::removeEmoji(Json::encode($order_data));
                $order->source = "Legacy";
                $order->status = $status;
                $order->order_id = $order_data[0]["id"];
                $order->branch = $order_data[0]["restaurant_token"];
                $order->user_id = \Yii::$app->user->getId();
            }

            $order->save();
            $transaction->commit();
            return Result::SUCCESS;
        } catch (\yii\db\Exception $ex) {
            $transaction->rollBack();
            \Yii::error($ex->getMessage());
        }
        return Result::FAILURE;
    }

    public function actionCancelOrder() {
        $order =  \Opis\Closure\serialize(\Yii::$app->request->post('order'));
        return UserAction::recieveOrder($order,'GlovoCancel');
    }


}