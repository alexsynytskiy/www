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
        'css/fonts.css',
        'css/jquery.bxslider.css',
        'css/jquery.datepick.css',
        'css/jquery.Jcrop.css',
        'css/icheck.css',
        'css/spinner.css',
        'css/loader.css',
        'css/less/selectize.css',
        'css/less/style.css',
        'css/less/style_olg.css',
    ];
    public $js = [
        'js/jquery.bxslider.js',
        'js/icheck.min.js',
        'js/datepicker/jquery.plugin.min.js',
        'js/datepicker/jquery.datepick.js',
        'js/datepicker/jquery.datepick-ru.js',
        'js/selectize.js',
        'js/jquery.indyMasonry.js',
        'js/autosize.min.js',
        'js/jquery.jcrop.js',
        'js/code.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
}
