<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Widget asset bundle
 */
class EditorAssetBundle extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@backend/assets';

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];

    /**
     * Register asset bundle language files and plugins.
     */
    public function registerAssetFiles($view)
    {
        $this->js[] = 'plugins/skip.js';
        $this->js[] = 'plugins/quote.js';
        parent::registerAssetFiles($view);
    }
}
