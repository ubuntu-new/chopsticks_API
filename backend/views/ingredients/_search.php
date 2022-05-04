<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\IngredientsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ingredients-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'name_ge') ?>

    <?= $form->field($model, 'name_ru') ?>

    <?= $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'isPremium') ?>

    <?php // echo $form->field($model, 'base') ?>

    <?php // echo $form->field($model, 'class_name') ?>

    <?php // echo $form->field($model, 'product_category_id') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'price') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
