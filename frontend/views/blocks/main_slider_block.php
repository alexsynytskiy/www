<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $top3News Array of common\models\Post
**/
Yii::$app->formatter->locale = 'ru-RU';
?>

<div class="top-news-slider">
    <div class="slider">
        <?php foreach ($top3News as $post) { 
            $image = $post->getAsset(\common\models\Asset::THUMBNAIL_BIG);
            ?>
        <div class="slide">
            <a href="<?= $post->getUrl() ?>">
                <img src="<?= $image->getFileUrl() ?>" class="image">
            </a>
            <div class="intro">
                <a href="<?= $post->getUrl() ?>" class="title"><?= $post->title ?></a>
                <div class="date">
                    <?= Yii::$app->formatter->asDate(strtotime($post->created_at),'d MMMM Y HH:mm') ?>
                </div>
                <div class="preview"><?= $post->getShortContent(100, 100) ?></div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>