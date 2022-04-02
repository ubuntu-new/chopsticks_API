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

echo "<pre>";
//print_r(\api\actions\OrdersActions::getGetOrders());
echo "</pre>";
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
                                <button  class="btn btn-primary cancelorder" data-text="ბოდიშს გიხდით, დიდი დატვირთვის გამო ვერ შევძლებთ მომსახურებას.
We are sorry, but due to high demand we cannot fulfill your order at this time.">Reject</button>
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
                                <button  class="btn btn-primary cancelorder" data-text="ბოდიშს გიხდით, თქვენს მიერ მითითებულ მისამართზე არ გვაქვს მიწოდების სერისი.

                                    We are sorry, but at this point we do not have delivery service for your location.">Reject</button>
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

<div id="changebranch" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-own">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id=""></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body blockDiv">
                <div class="row">
                    <input type="hidden" value="" id="changeAddress_id">
                    <input type="hidden" value="" id="changeAddress_methodid">
                    <input type="hidden" value="" id="changeAddress_methodtitle">
                    <div id="delivery_binder">
                        <div class="btn btn-primary sendchangeaddress" data-target="Delivery Vake">Delivery Vake</div> &nbsp;
                        <div class="btn btn-primary sendchangeaddress" data-target="Delivery Saburtalo">Delivery Saburtalo</div> &nbsp;
                        <div class="btn btn-primary sendchangeaddress" data-target="Delivery Digomi">Delivery Digomi</div> &nbsp;
                        <div class="btn btn-primary sendchangeaddress" data-target="Delivery Gldani">Delivery Gldani</div> &nbsp;
                    </div>
                    <div id="takeout_binder">
                        <div class="btn btn-primary sendchangeaddress" data-target="Takeout Vake">Takeout Vake</div> &nbsp;
                        <div class="btn btn-primary sendchangeaddress" data-target="Takeout Saburtalo">Takeout Saburtalo</div> &nbsp;
                        <div class="btn btn-primary sendchangeaddress" data-target="Takeout Digomi">Takeout Digomi</div> &nbsp;
                        <div class="btn btn-primary sendchangeaddress" data-target="Takeout Gldani">Takeout Gldani</div> &nbsp;
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>

<!--<input type="button" value="Send ZPL Label"
       onclick="writeToSelectedPrinter('^XA^WD*:*.*^XZ')">-->

<input type="button" value="Send ZPL Label"
       onclick="writeToSelectedPrinter('^XA' +

        '^CWT,E:ARIALUNI.TTF^CFT,30,30^CI28^FT0,0^FDლევან გობრონიძე^FS^XZ')">


<input type="button" value="Send ZPL Label"
       onclick="writeToSelectedPrinter('^XA' +
        '^CWZ,E:ARIALUNI.TTF^FS'+
        '^FO0,20^GFA,678,678,6,,I0E,003FC,003F8FC,003F0FE,003E1FF,:003F1FF8,003F3CF8,001FFCF8,071FFCF8,0F8FF8F87C,0FC7F8FCFE,0FC3F8F9FF,0FE1F1FBFF,060063FBFF,I0C07F3F8,001F03F7E,003FC0C7C,003FF0078,003FFE0F8,001IF8F,I03FFEF,I01JF,J07IF,J03IF,J01IFC,001C0FBFE,003F87CFE,003FE3E7F,003FFBE7F8,003JF778,I07IF79C,I01IF3DC,J03FF3FC,K0FF3FC,001E01C1FC,003F81C1FC,003FE0F0FC,003FF8F078,001JF8,:I0JF8,I07IF8,I03CFF8,001FE3F,003FF,003FF8,003FFE,I0IF8,I03FFE,J0IF8,J01FF8,K07F8,001E01F8,003F81E,003FE0E,001FF8F,001JF8,:I0JF8,I07IF8,I03CFF8,001FE3F,003FF,003FF8,007FFE,I0IF8,I03FFE,J0IF8,J01FF8,I0787F8,001FF1F8,003FFC7,003IF,007IF8,007IFE,007E1FE,007C07F,003CC1F86,003CC1F878,003FE0F87C,001FF0F83E,I0JFC3F,07E7IF83F,0FF3IF83F8,1FF9IF87F8,3FFC1FF07F8,3FFE07C0FF,7FFEI01FF,7E7FI03FE,7C3FI07FE,7C1F801FFC,7C0F807FF8,FC0F83FFE,FC0FDIFC,FC07JF,FC47IFE,FCKF,7CJFC,7EIFE,7EIF,7EIFC,7EJF,3F7IFE,3F0JFC,3F81IFC,1F803FFC,1FC00FFC,0FE001F8,07F8,03F,,^FS' +
        '^FO100,20^A0N,30,30^FDORDER #16548^FS'+
        '^FO100,50^CI28^AON,35,35^FDლევან გობრონიძე^FS'+
        '^FO100,100^A0N,30,30^FD577758595^FS'+
        '^XZ')">

<input type="button" value="Send ZPL Label"
       onclick="writeToSelectedPrinter('^XA^FO100,0^A0N,30,30^FDORDER #'
            +'151564^FS^FO30,0^A0,25,25^FDasdasd^FS' +
                    '^CWT,E:ARIALUNI.TTF^CFT,30,30^CI28^FT0,0^FH^FDლევან გობრონიძე^FS'+
              '^FO100,70^A0N,30,30^FD577758595^FS^XZ')">
