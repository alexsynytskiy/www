<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $top6News Array of common\models\Post
**/
?>

<?php foreach ($top6News as $post) { 
    $image = $post->getAsset(\common\models\Asset::THUMBNAIL_NEWS);
    $class = 'type-text';
    $class = $post->with_photo ? 'type-photo' : $class;
    $class = $post->with_video ? 'type-video' : $class;
    ?>
<div class="main-news <?= $class ?>">
    <div class="main-news-header">
        <div class="date"><?= date('d.m.Y', strtotime($post->created_at)) ?></div>
        <div class="right-sidebar">
            <div class="time"><?= date('H:i', strtotime($post->created_at)) ?></div>
            <?php $commentsCount = $post->getCommentsCount(); ?>
            <?php if($commentsCount > 0) { ?>
            <div class="comments">
                <div class="icon"></div>
                <div class="count"><?= $commentsCount ?></div>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="photo">
        <img src="<?= $image->getFileUrl() ?>" class="right-border">
        <div class="icon-logo"></div>
    </div>
    <div class="title">
        <a href="<?= $post->getUrl() ?>"><?= $post->title ?></a>
    </div>
</div>
<?php } ?>