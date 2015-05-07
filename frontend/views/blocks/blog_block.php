<?php
/**
 * @var $this yii\web\View
 * @var $posts Array of common\models\Post
**/
?>
<div class="blogs">
    <div class="title">
        <div class="title-main">Блоги</div>
        <div class="title-rss">Rss</div>
    </div>
    <?php 
    foreach ($posts as $post) { 
        $username = $post->user->getDisplayName();
        $avatar = $post->user->getAsset();
        $imageUrl = empty($avatar->getFileUrl()) ? $avatar->getDefaultFileUrl() : $avatar->getFileUrl();
    ?>
    <div class="blog-preview">
        <img src="<?= $imageUrl ?>" class="photo" alt="user avatar">
        <div class="about-info">
            <div class="author"><a href="#"><?= $username ?></a></div>
            <!-- <div class="popular"></div> -->
            <div class="date"><?= Yii::$app->formatter->asDate(strtotime($post->created_at),'full') ?></div>
        </div>
        <div class="intro">
            <a href="<?= \yii\helpers\Url::to(['blog/'.$post->id.'-'.$post->slug]) ?>"><?= $post->title ?></a>
        </div>
    </div>
    <?php } ?>
</div>
