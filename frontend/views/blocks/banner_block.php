<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $banner common\models\Banner
**/
?>

<div class="banner-box banner-<?= $banner->id ?>">
    <?= $banner->content ?>
</div>