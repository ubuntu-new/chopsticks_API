<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'duration')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'payment_method_id')->textInput() ?>

    <?= $form->field($model, 'delivery_method_id')->textInput() ?>

    <?= $form->field($model, 'order_data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'promise_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_discounted')->textInput() ?>

    <?= $form->field($model, 'accept_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'finish_date')->textInput() ?>

    <?= $form->field($model, 'driver_id')->textInput() ?>

    <?= $form->field($model, 'start_delivery')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'end_delivery')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'customer')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
