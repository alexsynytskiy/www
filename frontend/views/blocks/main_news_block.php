<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $top6News Array of common\models\Post
**/
?>

<?php foreach ($top6News as $post) { 
    $image = $post->getAsset(\common\models\Asset::THUMBNAIL_COVER);
    ?>
<div class="main-news">
    <div class="main-news-header">
        <div class="date"><?= date('d.m.Y', strtotime($post->created_at)) ?></div>
        <div class="right-sidebar">
            <div class="time"><?= date('H:i', strtotime($post->created_at)) ?></div>
            <div class="comments">
                <div class="icon"></div>
                <div class="count"><?= $post->comments_count ?></div>
            </div>
        </div>
    </div>
    <div class="photo">
        <img src="<?= $image->getFileUrl() ?>" class="text-border">
        <div class="icon-logo icon-text"></div>
    </div>
    <div class="title">
        <a href="<?= $post->getUrl() ?>"><?= $post->title ?></a>
    </div>
</div>
<?php } ?>