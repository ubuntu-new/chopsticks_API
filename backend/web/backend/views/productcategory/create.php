<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model api\models\database\webetrela\Productcategory */

$this->title = Yii::t('app', 'Create Productcategory');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Productcategories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="productcategory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
