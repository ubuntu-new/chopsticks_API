<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\Orders */

$this->title = $model->id;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Orders'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="orders-view">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'order_data:ntext',
//            'customer:ntext',
            [
                'attribute'=>'order_data',
                'headerOptions' => ['style' => 'width:250px'],
                'value' => function ($model) {
                    $order_data = json_decode($model->order_data);
                    $text = "";
                    if (isset($order_data))
                    {
                        $totalPrice = 0;
                        foreach($order_data as $row) {
                            $text .=("<strong>Product Name:</strong> <br><strong> ".$row->name."</strong>".
                                    "<br><strong>Quantity: </strong> ".$row->quantity)."
                                     <br><strong>Tottal Price: </strong> ".$row->price * $row->quantity."<br>";

                            if (isset($row->toppings)) {
                                $text .=("<strong>Toppings:</strong>")."<br>";
                                foreach ($row->toppings as $topping) {
                                    $text .=("<span style='padding-left: 10px'></span><strong>".$topping->name." :</strong> ".$topping->qty."<strong>X</strong> ".$topping->price)."<br>";
                                }
                            }

                            if (isset($row->sauce))
                            {
                                $text .=("<strong>Sauce:</strong>")."<br>";
                                foreach ($row->sauce as $sauce ) {
                                    $text .=("<span style='padding-left: 10px'></span><strong>".$sauce->name.": ".$sauce->qty)."</strong><br>";
                                }
                            }

                            if (isset($row->extras)){
                                $text .=("<span class='pl-3'><strong>Extra Toppings:</strong></span>")."<br>";
                                foreach ($row->extras as $extra) {
                                    $text .=("<span style='padding-left: 0px'></span><strong>".$extra->name." :</strong> ".$topping->qty." <strong>X</strong>" .$extra->price)."<br>";
                                }
                            }
                        }

                    }
                    return $text;

                },
                'format'=> ['raw'],
            ],
            [
                'attribute'=>'cutlery',
                'headerOptions' => ['style' => 'width:150px'],
                'value' => function ($model) {
                    $cutlery = json_decode($model->cutlery);
                    $text = "";
                    if (isset($cutlery))
                    {
                        $totalPrice = 0;
                        foreach($cutlery as $row) {
                            $text .=("<strong>Product Name:</strong> <br><strong> ".$row->name."</strong>".
                                    "<br><strong>Qty: </strong> ".$row->qty)."X".$row->price."
                                     <br><strong>Price: </strong> ".$row->price * $row->qty."<br>";
                        }

                    }

                    return $text;

                },
                'format'=> ['raw'],
            ],
        ],


    ]) ?>

</div>
