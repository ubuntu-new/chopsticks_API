<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\Products */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="products-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php $listData=ArrayHelper::map(\api\models\database\webetrela\Productcategory::find()->all(),'name','name');
    echo $form->field($model, 'category_name')->dropDownList($listData, ['prompt'=>'Choose...']);?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name_ge')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name_ru')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'description_ge')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'description_ru')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_sale')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'is_special')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'web')->dropDownList([ '0', '1', ], ['prompt' => '']) ?>





<!--    --><?//= $form->field($model, 'gallery')->dropDownList([ 0 => '0', 1 => '1', 13 => '13', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
