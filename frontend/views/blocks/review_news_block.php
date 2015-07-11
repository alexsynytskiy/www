<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $photoReviewNews array Array of common\models\Post
 * @var $videoReviewNews array Array of common\models\Post
**/

if(!isset($photoReviewNews)) $photoReviewNews = [];
if(!isset($videoReviewNews)) $videoReviewNews = [];

$videoReviewCount = count($videoReviewNews);
$photoReviewCount = count($photoReviewNews);
?>

<?php if ($photoReviewCount || $videoReviewCount) { ?>
<div class="video-photo-reports default-box">
    <?php if($photoReviewCount) { ?>
    <div class="photo-report">
        <div class="box-header">
            <div class="box-title">Фоторепортаж</div>
            <a href="<?= Url::to('/photos') ?>"><div class="box-link">Все фото:</div></a>
        </div>
        <div class="box-content">
            <ul class="photo-list">
                <?php foreach ($photoReviewNews as $album) { 
                    $image = $album->getFrontendCoverImageAsset(\common\models\Asset::THUMBNAIL_BIG);
                    ?>
                <li class="photo-preview">
                    <div class="image"><img src="<?= $image->getFileUrl() ?>" alt=""></div>
                    <div class="title"><a href="<?= $album->getUrl() ?>"><?= $album->title ?></a></div>
                    <?php // if($album->comments_count > 0) { ?>
                    <div class="comments">
                        <div class="count"><?php // echo $album->comments_count; ?></div>
                        <div class="icon"></div>
                    </div>
                    <?php // } ?>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

    <?php if($videoReviewCount) { ?>
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
                    <?php if($post->comments_count > 0) { ?>
                    <div class="comments">
                        <div class="count"><?= $post->comments_count ?></div>
                        <div class="icon"></div>
                    </div>
                    <?php } ?>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

</div>
<?php } ?>