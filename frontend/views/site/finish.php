<?php

use frontend\assets\FinishAsset;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

use api\models\response\Result;
use app\models\Orders;
use Automattic\WooCommerce\Client;


FinishAsset::register($this);
$branch = \api\actions\userActions::getBranch();
$phpData = [
    'getOrders' => Url::to(['orders/get-orders']),
    'get_orders_for_manager' => Url::to(['post/get-orders-by-date']),
    'updateOrderStatus' => Url::to(['orders/update-order-status']),
    'branch' => $branch
];

\Yii::$app->view->registerJs(
    "var phpData = " . Json::encode($phpData) . ";",
    View::POS_BEGIN);
?>

<pre>
    <?php
    $woocommerce = new Client(
        'https://www.ronnyspizza.com/',
        'ck_a696a04e96b0b1caaacefdd947395303ee8b6f69',
        'cs_b49750d7d0d4b680527c07993e2bd006afeb56a8',
        [
            'version' => 'wc/v3',
        ]
    );

    $data = [

    ];

    print_r($woocommerce->get("orders/16838", $data));
    ?>
</pre>
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><strong>Completed Orders</strong></h4>
        </div>
    </div>
</div>


    <div class="row">
        <div class='col-sm-3'>
            <input type="text" class="form-control" id='datetimepicker4' />
        </div>
        <div class="col-ms-1">
            <div class="btn btn-primary fillterbydate"><i class="ion-search"></i></div>
        </div>
        </div>



<div class="row">
    <div class="col-md-12" style="min-height: 200px;">
        <div class=" m-b-30 card-body">
            <div class="row bbb" id="finish"></div>
        </div>
    </div>
</div>


