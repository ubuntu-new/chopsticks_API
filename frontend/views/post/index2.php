
<style>
    .shape{
        border-style: solid; border-width: 0 70px 40px 0; float:right; height: 0px; width: 0px;
        -ms-transform:rotate(360deg); /* IE 9 */
        -o-transform: rotate(360deg);  /* Opera 10.5 */
        -webkit-transform:rotate(360deg); /* Safari and Chrome */
        transform:rotate(360deg);
    }
    .offer{
        background:#fff; border:1px solid #ddd; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); margin: 15px 0; overflow:hidden;min-height: 300px;
        max-height: 300px;
        overflow-y: auto;
    }
    .offer:hover {
        -webkit-transform: scale(1.1);
        -moz-transform: scale(1.1);
        -ms-transform: scale(1.1);
        -o-transform: scale(1.1);
        transform:rotate scale(1.1);
        -webkit-transition: all 0.4s ease-in-out;
        -moz-transition: all 0.4s ease-in-out;
        -o-transition: all 0.4s ease-in-out;
        transition: all 0.4s ease-in-out;
    }
    .shape {
        border-color: rgba(255,255,255,0) #d9534f rgba(255,255,255,0) rgba(255,255,255,0);
    }
    .offer-radius{
        border-radius:7px;
    }
    .offer-danger {	border-color: #d9534f; }
    .offer-danger .shape{
        border-color: transparent #d9534f transparent transparent;
    }
    .offer-success {	border-color: #5cb85c; }
    .offer-success .shape{
        border-color: transparent #5cb85c transparent transparent;
    }
    .offer-default {	border-color: #999999; }
    .offer-default .shape{
        border-color: transparent #999999 transparent transparent;
    }
    .offer-primary {	border-color: #428bca; }
    .offer-primary .shape{
        border-color: transparent #428bca transparent transparent;
    }
    .offer-info {	border-color: #5bc0de; }
    .offer-info .shape{
        border-color: transparent #5bc0de transparent transparent;
    }
    .offer-warning {	border-color: #f0ad4e; }
    .offer-warning .shape{
        border-color: transparent #f0ad4e transparent transparent;
    }

    .shape-text{
        color:#fff; font-size:12px; font-weight:bold; position:relative; right:-40px; top:2px; white-space: nowrap;
        -ms-transform:rotate(30deg); /* IE 9 */
        -o-transform: rotate(360deg);  /* Opera 10.5 */
        -webkit-transform:rotate(30deg); /* Safari and Chrome */
        transform:rotate(30deg);
    }
    .offer-content{
        padding:0 20px 10px;
    }
    @media (min-width: 487px) {
        .container {
            max-width: 750px;
        }
        .col-sm-6 {
            width: 50%;
        }
    }
    @media (min-width: 900px) {
        .container {
            max-width: 970px;
        }
        .col-md-4 {
            width: 33.33333333333333%;
        }
    }

    @media (min-width: 1200px) {
        .container {
            max-width: 1170px;
        }
        .col-lg-3 {
            width: 25%;
        }
    }
    }
</style>
<?php

/* @var $this yii\web\View */




use Automattic\WooCommerce\Client;
use frontend\assets\PostAsset;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

PostAsset::register($this);
$branch = \api\actions\userActions::getBranch();

$phpData = [
    'getOrders' => Url::to(['post/get-orders']),
    'updateOrders' => Url::to(['post/update-orders']),
    'branch' => $branch
];

\Yii::$app->view->registerJs(
    "var phpData = " . Json::encode($phpData) . ";",
    View::POS_BEGIN);


$this->title = 'My Yii Application';


?>
<div class="site-index">

    <div class="container">
        <div class="row" style="margin-top: 50px">
            <div class="col-sm-12">
                <h2>Pending Orders</h2>
            </div>
            <div class="site-index">
                <div class="body-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-dark table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">Order</th>
                                    <th scope="col">name</th>
                                    <th scope="col">price</th>
                                    <th scope="col">date</th>
                                    <th scope="col">status</th>
                                    <th scope="col">shipping</th>
                                    <th scope="col">delivery</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody id="orders">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 50px">
            <div class="col-sm-12">
                <h2>In the Oven</h2>
            </div>
            <div class="site-index">
                <div class="body-content">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-dark table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">Order</th>
                                    <th scope="col">name</th>
                                    <th scope="col">price</th>
                                    <th scope="col">date</th>
                                    <th scope="col">status</th>
                                    <th scope="col">shipping</th>
                                    <th scope="col">delivery</th>
                                    <th scope="col">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody id="orders_start">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
