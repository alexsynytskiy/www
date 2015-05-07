<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/jquery.bxslider.css',
        'css/jquery.datepick.css',
        'css/jquery.Jcrop.css',
        'css/icheck.css',
        'css/less/selectize.less',
        'css/less/style.less',
        'css/less/style_olg.less',
    ];
    public $js = [
        'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js',
        'js/jquery.bxslider.js',
        'js/icheck.min.js',
        'js/datepicker/jquery.plugin.min.js',
        'js/datepicker/jquery.datepick.js',
        'js/datepicker/jquery.datepick-ru.js',
        'js/selectize.js',
        'js/jquery.indyMasonry.js',
        'js/code.js',
    ];
    public $depends = [
        // 'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
}
