<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\database\webetrela\ProductsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Products'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'w_id',
//            'category_id',
//            'category_name',
            'name',
            //'price:ntext',
            //'weight',
            //'class_name',
            //'is_special',
            //'created_at',
            //'status',
            //'web',
            //'nutritional:ntext',
            //'description:ntext',
            //'is_sticks',
            //'price_sale',
            //'gallery',
            //'is_promo',
            'name_ge',
            'name_ru',
            //'description_ge:ntext',
            //'description_ru:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
