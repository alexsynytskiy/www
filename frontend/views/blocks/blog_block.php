<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $posts Array of common\models\Post
 * @var $selectedBlogs Array of common\models\SelectedBlog
**/
?>
<div class="blogs">
    <div class="title">
        <div class="title-main"><a href="<?= Url::to('/blogs') ?>">Блоги</a></div>
    </div>

    <?php 
    foreach ($selectedBlogs as $post) { 
        $userName = $post->user->getDisplayName();
        $avatar = $post->user->getAsset();
        $imageUrl = $avatar->getFileUrl();
    ?>
    <div class="blog-preview">
        <a href="<?= Url::to('/blogs/'.$post->user->id) ?>">
            <img src="<?= $imageUrl ?>" class="photo" alt="user avatar">
        </a>
        <div class="about-info">
            <div class="author">
                <a href="<?= Url::to('/blogs/'.$post->user->id) ?>">
                    <?= $userName ?>
                </a>
            </div>
            <div class="popular"></div>
            <div class="date">
                <?= Yii::$app->formatter->asDate(strtotime($post->created_at),'d MMMM Y HH:mm') ?>
            </div>
        </div>
        <div class="intro">
            <a href="<?= $post->getUrl() ?>"><?= $post->title ?></a>
        </div>
    </div>
    <?php }
    
    foreach ($posts as $post) { 
        $userName = $post->user->getDisplayName();
        $avatar = $post->user->getAsset();
        $imageUrl = $avatar->getFileUrl();
    ?>
    <div class="blog-preview">
        <a href="<?= Url::to('/blogs/'.$post->user->id) ?>">
            <img src="<?= $imageUrl ?>" class="photo" alt="user avatar">
        </a>
        <div class="about-info">
            <div class="author">
                <a href="<?= Url::to('/blogs/'.$post->user->id) ?>">
                    <?= $userName ?>
                </a>
            </div>
            <!-- <div class="popular"></div> -->
            <div class="date">
                <?= Yii::$app->formatter->asDate(strtotime($post->created_at),'d MMMM Y HH:mm') ?>
            </div>
        </div>
        <div class="intro">
            <a href="<?= $post->getUrl() ?>"><?= $post->title ?></a>
        </div>
    </div>
    <?php } ?>
</div>
