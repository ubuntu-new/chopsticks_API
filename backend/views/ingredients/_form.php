<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\Ingredients */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ingredients-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ge')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weight')->textInput() ?>

    <?= $form->field($model, 'isPremium')->textInput() ?>

    <?= $form->field($model, 'base')->textInput() ?>

    <?= $form->field($model, 'class_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_category_id')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ '0', '1', '2', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
