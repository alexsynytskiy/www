<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $album common\models\Album
 * @var $photo common\models\Asset
**/

?>

<div class="default-box album-box">
    <div class="top-block">
        <div class="date-icon"></div>
        <div class="date-text">
            <?= Yii::$app->formatter->asDate(strtotime($album->created_at),'d MMMM Y HH:mm') ?>
        </div>
    </div>
    <div class="album-container">
        <div class="title">
            <a href="<?= $album->getUrl() ?>">
                <?= $album->title ?>
            </a>
        </div>

        <img class="album-image" src="<?= $photo->getFileUrl() ?>">

    </div>
</div>

