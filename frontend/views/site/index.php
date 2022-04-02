<?php

use frontend\assets\SiteAsset;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use Automattic\WooCommerce\Client;


SiteAsset::register($this);
$branch = \api\actions\userActions::getBranch();
$userid = Yii::$app->user->getId();
$phpData = [
    'getOrders' => Url::to(['orders/get-orders']),
    'get_orders_for_manager' => Url::to(['post/get-orders']),
    'updateOrderStatus' => Url::to(['orders/update-order-status']),
    'cancelOrder' => Url::to(['orders/cancel-order']),
    'updateAddress' => Url::to(['orders/change-order-address']),
    'branch' => $branch,
    'userid' => $userid
];

//
$woocommerce = new Client(
    'https://ronnys.ge/site',
    'ck_a696a04e96b0b1caaacefdd947395303ee8b6f69',
    'cs_b49750d7d0d4b680527c07993e2bd006afeb56a8',
    [
        'version' => 'wc/v3',
    ]
);


//
//$data = [
//
//
//        'shipping_lines' => [0=>[
//            'id' => 1085,
//            'method_title' => 'Delivery Vake',
//
//        ]]
//
//
//
//];
//
//$woocommerce->put('orders/16664', $data);
$t = \api\models\database\TestOrders::find()->where(["id"=>3])->one();

//echo "<pre>";


// Declare two dates
$end_date = strtotime("2020-08-25T22:18:42.000Z");
$start_date = strtotime("020-08-25T21:33:42.000Z");

// Get the difference and divide into
// total no. seconds 60/60/24 to get
// number of days
// echo "Difference between two dates: "
//    . ($end_date - $start_date)/60;


//print_r(\Opis\Closure\unserialize($t->data));/
//echo "</pre>";
\Yii::$app->view->registerJs(
    "var phpData = " . Json::encode($phpData) . ";",
    View::POS_BEGIN);
?>
<audio controls  id="song" style="display: none">

    <source src="<?=Yii::getAlias("@web")?>/sound/sound.mp3" type="audio/mpeg">

</audio>
<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><strong>Pending Orders</strong></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class=" m-b-30 card-body">
            <div class="row aaa" id="orders">

                <!--                <div class=" aab col-md-4 col-sm-3 col-lg-3">-->
                <!--                    <div class="card ">-->
                <!--                           <div class="card-body">-->
                <!--                               <h4 class="mt-0 header-title">http</h4>-->
                <!--                            <div class="row m-t-10">-->
                <!--                            <div class="col-md-12">-->
                <!--                                    deliveru-->
                <!--                                    </div></div>-->
                <!--                            <div class="row m-t-10">-->
                <!--                                item item-->
                <!--                                </div>-->
                <!--                            <div class="row m-t-10">-->
                <!--                                <div class="col-12 text-right">-->
                <!--                                    <span class="f_text">value.total</span>-->
                <!--                                    </div>-->
                <!--                                <div class='col-md-2 m-t-10'><button class='updateOrder btn btn-danger waves-effect waves-light' data-status='5' data-orderid='12'><i class=' mdi mdi-close-circle-outline'></i></button></div>-->
                <!--                                <div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='20' data-status='1' data-orderid='12'>20</button></div>-->
                <!--                                <div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='30' data-status='1' data-orderid='12'>30</button></div>-->
                <!--                                <div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='40' data-status='1' data-orderid='12'>40</button></div>-->
                <!--                                <div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='60' data-status='1' data-orderid='12'>60</button></div>-->
                <!--                                <div class='col-md-2 m-t-10'><button class='updateOrderKDS btn btn-primary waves-effect waves-light' data-duration='80' data-status='1' data-orderid='12'>80</button></div>-->
                <!--                                </div>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                    </div>-->

            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><strong>Preparing in Kitchen</strong></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class=" m-b-30 card-body">
            <div class="row bbb" id="in-process"></div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><strong>Completed Orders</strong></h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class=" m-b-30 card-body">
            <div class="row ccc" id="completed"></div>
        </div>
    </div>
</div>



<div id="mymodal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-own">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body blockDiv">
                <div class="row">
                    <div class="col-4">
                        <div class="card widget-user m-b-20">
                            <div class="widget-user-desc p-4 text-center bg-primary position-relative">
                               <!--<span class="text-white"> Close Soon </span>-->
                                <p class="text-white mb-0 ">
                                    ბოდიშს გიხდით, დიდი დატვირთვის გამო ვერ შევძლებთ მომსახურებას.
                                    <br>
                                    We are sorry, but due to high demand we cannot fulfill your order at this time.
                                </p>
                            </div>
                            <div class="p-4">
                                <button  class="btn btn-primary cancelorder" data-text="ბოდიშს გიხდით, დიდი დატვირთვის გამო ვერ შევძლებთ მომსახურებას.<br> We are sorry, but due to high demand we cannot fulfill your order at this time.">Reject</button>
                                <input type="hidden" id="cancel_order_id">
                                <input type="hidden" id="cancel_user">
                                <input type="hidden" id="cancel_mail">
                            </div>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card widget-user m-b-20">
                            <div class="widget-user-desc p-4 text-center bg-primary position-relative">
                               <!--<span class="text-white">  delivery service  </span>-->

                                <p class="text-white mb-0 ">
                                    ბოდიშს გიხდით, თქვენს მიერ მითითებულ მისამართზე არ გვაქვს მიწოდების სერისი.
                                    <br>
                                    We are sorry, but at this point we do not have delivery service for your location.
                                </p>
                            </div>
                            <div class="p-4">
                                <button  class="btn btn-primary cancelorder" data-text="ბოდიშს გიხდით, თქვენს მიერ მითითებულ მისამართზე არ გვაქვს მიწოდების სერვისი. <br> We are sorry, but at this point we do not have delivery service for your location.">Reject</button>
                            </div>

                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card widget-user m-b-20">
                            <div class="widget-user-desc p-4 text-center bg-primary position-relative">
                                <span class="text-white ">Enter your text </span>
                                <p class="text-white mb-0 ">
                                   <textarea class="form-control" id="costum_notification"></textarea>
                                </p>
                            </div>
                            <div class="p-4">
                                <button  class="btn btn-primary cancelorder" data-text="1">Reject</button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<div id="changebranch" class="modal fade bd-example-modal-lg"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-own" style="max-width: 800px !important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="">Change branch</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body blockDiv">


                    <input type="hidden" value="" id="changeAddress_id">
                    <input type="hidden" value="" id="changeAddress_methodid">
                    <input type="hidden" value="" id="changeAddress_methodtitle">
                    <div id="delivery_binder" style="width: 100%">
                        <table>
                            <tr>
                                <td><div class="btn btn-primary sendchangeaddress" data-target="Delivery Vake">Delivery Vake</div> &nbsp;</td>
                                <td><div class="btn btn-primary sendchangeaddress" data-target="Delivery Saburtalo">Delivery Saburtalo</div></td>
                                <td><div class="btn btn-primary sendchangeaddress" data-target="Delivery Digomi">Delivery Digomi</div> &nbsp;</td>
                                <td><div class="btn btn-primary sendchangeaddress" data-target="Delivery Gldani">Delivery Gldani</div> &nbsp;</td>
                            </tr>
                        </table>
                    </div>
                    <div id="takeout_binder" style="width: 100%">
                        <table>
                            <tr>
                                <td><div class="btn btn-primary sendchangeaddress"  data-target="Takeout Vake">Takeout Vake</div> &nbsp;</td>
                                <td><div class="btn btn-primary sendchangeaddress"  data-target="Takeout Saburtalo">Takeout Saburtalo</div> &nbsp;</td>
                                <td><div class="btn btn-primary sendchangeaddress" data-target="Takeout Digomi">Takeout Digomi</div></td>
                                <td><div class="btn btn-primary sendchangeaddress"  data-target="Takeout Gldani">Takeout Gldani</div></td>
                            </tr>
                        </table>&nbsp;
                    </div>



            </div>

        </div>
    </div>
</div>