<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class SiteprintAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugins/pnotify/PNotifyBrightTheme.css',
        'css/site/index.css'

    ];
    public $js = [
        'js/BrowserPrint-3.0.216.min.js',
        'plugins/pnotify/PNotify.js',
        'plugins/pnotify/PNotifyStyleMaterial.js',
        'js/masonry.pkgd.min.js',
        'js/site/index_print.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
