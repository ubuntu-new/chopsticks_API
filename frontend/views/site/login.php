<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper-page">
<div class="card">
    <div class="card-body">

        <h3 class="text-center m-0">
            <a href="index.html" class="logo logo-admin"><img src="<?=Yii::getAlias("@web")?>/images/r_logo.png" height="30" alt="logo"></a>
        </h3>

        <div class="p-3">
            <h4 class="text-muted font-18 m-b-5 text-center">Welcome Back !</h4>


            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>


            <div class="form-group row m-t-20">
                <div class="col-6">
                    <div class=" custom-checkbox">
                        <?= $form->field($model, 'rememberMe')->checkbox() ?>
                    </div>
                </div>
                <div class="col-6 text-right">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary w-md waves-effect waves-light', 'name' => 'login-button']) ?>

                </div>
            </div>




            <?php ActiveForm::end(); ?>


        </div>

    </div>
</div>
    </div>


