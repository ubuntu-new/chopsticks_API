

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


$this->title = 'Ronny';


?>


<audio controls  id="song" style="display: none">

    <source src="<?=Yii::getAlias("@web")?>/sound/sound.mp3" type="audio/mp3">

</audio>

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><strong>New Orders</strong></h4>





        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class=" m-b-30 card-body">
            <div class="row aaa" id="orders"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title"><strong>Sent to Oven</strong></h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class=" m-b-30 card-body">
            <div class="row bbb" id="orders_start"></div>
        </div>
    </div>
</div>



