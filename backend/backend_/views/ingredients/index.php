<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel api\models\database\webetrela\IngredientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Ingredients');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingredients-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Ingredients'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'name_ge',
            'name_ru',
            'weight',
            //'isPremium',
            //'base',
            //'class_name',
            //'product_category_id',
            //'status',
            //'price',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
