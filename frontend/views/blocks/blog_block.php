<?php
/**
 * @var $this yii\web\View
 * @var $posts Array of common\models\Post
**/
?>
<div class="blogs">
    <div class="title">
        <div class="title-main"><a href="<?= \yii\helpers\Url::to('/blogs') ?>">Блоги</a></div>
        <div class="title-rss"><a href="<?= \yii\helpers\Url::to('/rss') ?>">Rss</a></div>
    </div>
    <?php 
    foreach ($posts as $post) { 
        $username = $post->user->getDisplayName();
        $avatar = $post->user->getAsset();
        $imageUrl = empty($avatar->getFileUrl()) ? $avatar->getDefaultFileUrl() : $avatar->getFileUrl();
    ?>
    <div class="blog-preview">
        <a href="<?= \yii\helpers\Url::to('/user/profile/'.$post->user->id) ?>">
            <img src="<?= $imageUrl ?>" class="photo" alt="user avatar">
        </a>
        <div class="about-info">
            <div class="author"><a href="<?= \yii\helpers\Url::to('/user/profile/'.$post->user->id) ?>"><?= $username ?></a></div>
            <!-- <div class="popular"></div> -->
            <div class="date"><?= Yii::$app->formatter->asDate(strtotime($post->created_at),'full') ?></div>
        </div>
        <div class="intro">
            <a href="<?= \yii\helpers\Url::to(['/blog/'.$post->id.'-'.$post->slug]) ?>"><?= $post->title ?></a>
        </div>
    </div>
    <?php } ?>
</div>
