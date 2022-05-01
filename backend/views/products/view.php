<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\Products */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="products-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'w_id',
            'category_id',
            'category_name',
            'name',
            'price:ntext',
            'weight',
            'class_name',
            'is_special',
            'created_at',
            'status',
            'web',
            'nutritional:ntext',
            'description:ntext',
            'is_sticks',
            'price_sale',
            'gallery',
            'is_promo',
            'name_ge',
            'name_ru',
            'description_ge:ntext',
            'description_ru:ntext',
        ],
    ]) ?>

</div>
