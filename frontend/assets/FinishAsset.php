<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class FinishAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugins/pnotify/PNotifyBrightTheme.css',
        'css/site/index.css',
        'css/bootstrap-datetimepicker.css'

    ];
    public $js = [
        'plugins/pnotify/PNotify.js',
        'plugins/pnotify/PNotifyStyleMaterial.js',
        'js/soundmanager2.js',
        'js/masonry.pkgd.min.js',
        'js/moment-with-locales.js',
        'js/bootstrap-datetimepicker.min.js',
        'js/site/finish.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
