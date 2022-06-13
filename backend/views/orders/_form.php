<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\Orders */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="orders-form">

    <?php $form = ActiveForm::begin(); ?>

<!--    --><?/*= $form->field($model, 'user_id')->textInput() */?>

 <!--   --><?/*= $form->field($model, 'duration')->textInput(['maxlength' => true]) */?>

<!--    --><?//= $form->field($model, 'status')->dropDownList([ 0 => '0', 1 => 'Pending', 2 => '2', 3 => '3', 4 => '4', 5 => '5', 13 => '13' ], ['prompt' => '']) ?>

    <?php $statuses=ArrayHelper::map(\api\models\database\OrderStatus::find()->all(), 'id', 'status_name');

    echo $form->field($model, 'status')->dropDownList($statuses, ['prompt'=>'Choose...']);
    ?>

    <?php $listData=ArrayHelper::map(\api\models\database\webetrela\User::find()->where(['position'=>'Driver'])->all(),'id','fullname');

    echo $form->field($model, 'driver_name')->dropDownList($listData, ['prompt'=>'Choose...']);
?>

   <!-- <?/*= $form->field($model, 'payment_method_id')->textInput() */?>

    <?/*= $form->field($model, 'delivery_method_id')->textInput() */?>

    --><?/*= $form->field($model, 'order_data')->textarea(['rows' => 6]) */?>

    <?/*= $form->field($model, 'promise_date')->textInput(['maxlength' => true]) */?><!--

    <?/*= $form->field($model, 'is_discounted')->textInput() */?>

    <?/*= $form->field($model, 'accept_date')->textInput(['maxlength' => true]) */?>

    <?/*= $form->field($model, 'finish_date')->textInput() */?>

    <?/*= $form->field($model, 'driver_id')->textInput() */?>

    <?/*= $form->field($model, 'start_delivery')->textInput(['maxlength' => true]) */?>

    <?/*= $form->field($model, 'end_delivery')->textInput(['maxlength' => true]) */?>

    <?/*= $form->field($model, 'created_at')->textInput() */?>

    --><?/*= $form->field($model, 'updated_at')->textInput() */?>

<!--    --><?/*= $form->field($model, 'created_by')->textInput() */?>

   <!-- --><?/*= $form->field($model, 'customer')->textarea(['rows' => 6]) */?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
