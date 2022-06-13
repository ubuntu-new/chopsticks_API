<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\AboutSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="about-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'created') ?>

    <?php // echo $form->field($model, 'title_ge') ?>

    <?php // echo $form->field($model, 'description_ge') ?>

    <?php // echo $form->field($model, 'title_ru') ?>

    <?php // echo $form->field($model, 'description_ru') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
