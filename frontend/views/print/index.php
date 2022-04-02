<?php

use api\models\database\Orders;

$t = Orders::find()->where(["id"=>3978])->one();
echo  "<pre>";
$t = \yii\helpers\Json::decode($t["order_data"]);
print_r($t["totalPrice"]);
echo  "</pre>";
// A sample PHP Script to POST data using cURL
// Data in JSON format

/*$data = array('menuUrl'=>'https://mn.ronnyspizza.ge/menu.json');

$payload = json_encode($data);

// Prepare new cURL resource
$ch = curl_init('https://stageapi.glovoapp.com/webhook/stores/ronnys-123/menu');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

// Set HTTP Header for POST request
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: 926c638c-e6f0-4f90-9114-1122a0ac7768')
);

// Submit the POST request
$result = curl_exec($ch);
print_r($result);
// Close cURL session handle
curl_close($ch);*/


/*
$data = array('available'=>true);

$payload = json_encode($data);

$ch = curl_init('https://stageapi.glovoapp.com/webhook/stores/ronnys-123/products/burger_1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: 926c638c-e6f0-4f90-9114-1122a0ac7768')
);

$result = curl_exec($ch);
print_r($result);
curl_close($ch);
*/
?>


