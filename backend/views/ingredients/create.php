<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\Ingredients */

$this->title = Yii::t('app', 'Create Ingredients');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Ingredients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ingredients-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
