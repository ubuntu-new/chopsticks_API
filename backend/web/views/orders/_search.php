<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\OrdersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'duration') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'payment_method_id') ?>

    <?php // echo $form->field($model, 'delivery_method_id') ?>

    <?php // echo $form->field($model, 'order_data') ?>

    <?php // echo $form->field($model, 'promise_date') ?>

    <?php // echo $form->field($model, 'is_discounted') ?>

    <?php // echo $form->field($model, 'accept_date') ?>

    <?php // echo $form->field($model, 'finish_date') ?>

    <?php // echo $form->field($model, 'driver_id') ?>

    <?php // echo $form->field($model, 'start_delivery') ?>

    <?php // echo $form->field($model, 'end_delivery') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'customer') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
