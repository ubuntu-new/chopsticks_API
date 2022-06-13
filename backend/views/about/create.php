<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\About */

$this->title = Yii::t('app', 'Create About');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Abouts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="about-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
