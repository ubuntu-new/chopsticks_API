<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'plugins/morris/morris.css',
        'css/bootstrap.min_new.css',
        'css/metismenu.min.css',
        'css/icons.css',
        'css/style.css',
    ];
    public $js = [
        'js/bootstrap.bundle.min.js',
        'js/metisMenu.min.js',
        'js/jquery.slimscroll.js',
        'js/waves.min.js',
        'plugins/jquery-sparkline/jquery.sparkline.min.js',
//        'plugins/morris/morris.min.js',
        'plugins/raphael/raphael-min.js',
//        'js/pages/dashboard.js',
        'js/app.js',
        'js/jquery.blockUI.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
