<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="enlarged">
<?php $this->beginBody() ?>

<div id="wrapper">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>


    <?php (Yii::$app->user->isGuest? "": require_once 'menu.php'); ?>
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container-fluid">
        <?= $content ?>
            </div>
        </div>
    </div>
</div>


<footer class="footer">
    © 2020 - Ronnys  <span class="d-none d-sm-inline-block"> Crafted with <i class="mdi mdi-heart text-danger"></i> by Webertela</span>.
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
