<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var $photoReviewNews array Array of common\models\Post
 * @var $videoReviewNews array Array of common\models\Post
**/
?>

<div class="video-photo-reports default-box">

    <div class="photo-report">
        <div class="box-header">
            <div class="box-title">Фоторепортаж</div>
            <a href="#"><div class="box-link">Все фото:</div></a>
        </div>
        <div class="box-content">
            <ul class="photo-list">
                <?php foreach ($photoReviewNews as $post) { 
                    $image = $post->getAsset(\common\models\Asset::THUMBNAIL_BIG);
                    ?>
                <li class="photo-preview">
                    <div class="image"><img src="<?= $image->getFileUrl() ?>" alt=""></div>
                    <div class="title"><a href="<?= $post->getUrl() ?>"><?= $post->title ?></a></div>
                    <div class="comments">
                        <div class="count"><?= $post->comments_count ?></div>
                        <div class="icon"></div>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="video-report">
        <div class="red-box-header">
            <div class="box-title">Видеорепортаж</div>
            <a href="#"><div class="box-link">Все видео:</div></a>
        </div>
        <div class="box-content">
            <ul class="video-list">
                <?php foreach ($videoReviewNews as $post) { 
                    $image = $post->getAsset(\common\models\Asset::THUMBNAIL_BIG);
                    ?>
                <li class="video-preview">
                    <div class="image"><img src="<?= $image->getFileUrl() ?>" alt=""></div>
                    <div class="title"><a href="<?= $post->getUrl() ?>"><?= $post->title ?></a></div>
                    <div class="comments">
                        <div class="count"><?= $post->comments_count ?></div>
                        <div class="icon"></div>
                    </div>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>