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
                'value' => function ($model) {
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
        ],
    ]) ?>

</div>
