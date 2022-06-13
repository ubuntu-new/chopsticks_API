<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel api\models\database\webetrela\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Orders'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute'=>'order_data',
                'value' => function ($model, $key, $index, $column) {
                    $order_data = json_decode($model->order_data);
                    $text = "";
                    if (isset($order_data))
                    {
                        $totalPrice = 0;
                        foreach($order_data as $row) {
                            $text .=("<strong>Product Name:</strong> ".$row->name."<br><strong>Full Price:</strong> ".$row->price * $row->quantity."<strong>Quantity:</strong> ".$row->quantity)."<br>";
                            $totalPrice = $totalPrice + ($row->price * $row->quantity);

                            if (isset($row->toppings)) {
                                $text .=("<strong>Toppings:</strong>")."<br>";
                                foreach ($row->toppings as $topping) {
                                    $text .=("<span style='padding-left: 30px'></span>".$topping->name." <strong>:</strong> ".$topping->qty."<strong>:</strong> ".$topping->price)."<br>";
                                }
                            }

                            if (isset($row->sauce))
                            {
                                $text .=("<strong>Sauce:</strong>")."<br>";
//                                foreach ($row->sauce as $sauce ) {
                                    $text .=("<span style='padding-left: 30px'></span>".$row->sauce->name .$topping->price)."<br>";
//                                }
                            }

                            if (isset($row->extras)){
                                $text .=("<span class='pl-3'><strong>Extra Toppings:</strong></span>")."<br>";
                                foreach ($row->extras as $extra) {
                                    $text .=("<span style='padding-left: 30px'></span>".$extra->name." <strong>:</strong> ".$extra->price)."<br>";
                                }
                            }
                        }

                    }

                    return $text;

                },
                'format'=> ['raw'],
            ],
            [
                'attribute'=>'customer',
                'value' => function ($model, $key, $index, $column) {
                    $customer = json_decode($model->customer);
                    $address = "";
                    $address .= ("<span class='pl-3'><strong> &nbsp;</strong></span>").$customer->fullName ."<br>";
                    $address .= ("<span class='pl-3'><strong> Phone:  &nbsp;</strong></span>")."<br>";
                    $address .= ("<span class='pl-3'><strong> &nbsp;</strong></span>").$customer->phone ."<br>";
                    $address .= ("<span class='pl-3'><strong> Address:  &nbsp;</strong></span>") ."<br>";
                    $address .= ("<span class='pl-3'><strong> &nbsp;</strong></span>").$customer->address ."<br>";
                    $address .= ("<span class='pl-3'><strong> MAP URL:  &nbsp;</strong></span>") ."<br>";
                    $address .= ("<span class='pl-3'><strong> &nbsp;</strong></span>").$customer->mapURL ."<br>";
                    $address .= ("<span class='pl-3'><strong> Email:  &nbsp;</strong></span>") ."<br>";
                    $address .= ("<span class='pl-3'><strong> &nbsp;</strong></span>").$customer->email ."<br>";
                    $address .= ("<span class='pl-3'><strong> Enterance:  &nbsp;</strong></span>") ."<br>";
                    $address .= ("<span class='pl-3'><strong> &nbsp;</strong></span>").$customer->enterance ."<br>";
                    $address .= ("<span class='pl-3'><strong> Security:  &nbsp;</strong></span>") ."<br>";
                    $address .= ("<span class='pl-3'><strong> &nbsp;</strong></span>").$customer->security ."<br>";
                    $address .= ("<span class='pl-3'><strong> Floor:  &nbsp;</strong></span>") ."<br>";
                    $address .= ("<span class='pl-3'><strong> &nbsp;</strong></span>").$customer->floor ."<br>";
                    $address .= ("<span class='pl-3'><strong> Flat:  &nbsp;</strong></span>")."<br>";
                    $address .= ("<span class='pl-3'><strong> &nbsp;</strong></span>").$customer->flat ."<br>";

                    return $address;

                },
                'format'=> ['raw'],
            ],

//            "order_data",
//            'duration',
//            [
//                'attribute'=>'PRINT TICKET',
//                'value' => function ($model, $key, $index, $column) {
//                    $order_data = json_decode($model->order_data);
//                    $text = "";
//                    if (isset($order_data))
//                    {
//                        $totalPrice = 0;
//                        foreach($order_data as $row) {
//                            $text .=("<strong>Product Name:</strong> ".$row->name."<br><strong>Full Price:</strong> ".$row->price * $row->quantity."<strong>Quantity:</strong> ".$row->quantity)."<br>";
//                            $totalPrice = $totalPrice + ($row->price * $row->quantity);
//
//                        }
//
//                    }
//
//                    return $totalPrice;
//
//                },
//                'format'=> ['raw'],
//            ],

            [
                'attribute'=>'Price SUM',
                    'value' => function ($model, $key, $index, $column) {
                    $order_data = json_decode($model->order_data);
                    $text = "";
                    if (isset($order_data))
                    {
                        $totalPrice = 0;
                        foreach($order_data as $row) {
                            $text .=("<strong>Product Name:</strong> ".$row->name."<br><strong>Full Price:</strong> ".$row->price * $row->quantity."<strong>Quantity:</strong> ".$row->quantity)."<br>";
                            $totalPrice = $totalPrice + ($row->price * $row->quantity);

                        }

                    }

                    return $totalPrice;

                },
                'format'=> ['raw'],
            ],
            [
                'attribute'=>'status',
                'value' => function ($model, $key, $index, $column) {
                    $status ="";
                    $status = $model->status;

                    switch ($status) {
                        case 1:
                           echo $status = "<span class='alert'>Pending Order!!!</span>";
                            break;
                        case 2:
                            echo $status = "<span class='success'>THANK YOU, YOUR ORDER HAS BEEN RECEIVED!</span>";
                            break;
                        case 5:
                            echo $status = "<span class='success'>ORDER IS READY AND DELIVERED</span>";
                            break;
                        case 6:
                            echo $status = "<span class='success'>YOUR ORDER IS ON THE WAY!</span>";
                            break;
                        case 11:
                            $status = "<span class='success'>SORRY, ALL COURIERS ARE BUSY AT THE MOMENT!</span>";
                            break;
//                        default:
//                            echo "i is not equal to 0, 1 or 2";
                    }



//                    if($status == 1)
//                    {
//                       $status = ('<span class="alert">Pending Order!!!</span>');
//                    }
                    return $status;
                },
                'format'=> ['raw'],
            ],
            [
                'attribute'=>'payment_method_id',
                'value' => function ($model, $key, $index, $column) {

                    $payment_method_id = $model->payment_method_id;
                    if($payment_method_id == 0)
                    {
                       $payment_method_id = ('<span>Cach On Delivery!!!</span>');
                    }
                    return $payment_method_id;
                },
                'format'=> ['raw'],
            ],
//            'status',
//            'payment_method_id',


//            ['class' => 'yii\grid\ActionColumn'],
            [

                'class' => 'yii\grid\ActionColumn',

                'template' => '{view} {update} {images} {delete}',

                'buttons' => [

                    'images' => function ($url, $model, $key) { // <--- here you can override or create template for a button of a given name

                        return Html::a('<span class="glyphicon glyphicon glyphicon-picture" aria-hidden="true"></span>', Url::to(['print', 'id' => $model->id]));

                    }

                ],

            ],
        ],
    ]);


    ?>

    <?php Pjax::end(); ?>

</div>
