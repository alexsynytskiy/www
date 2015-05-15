<?php 
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $model common\model\Post
**/

$userName = $model->user->getDisplayName();
$avatar = $model->user->getAsset();
$imageUrl = $avatar->getFileUrl();
$postDate = Yii::$app->formatter->asDate($model->created_at, 'dd MMMM YYYY HH:mm');
?>
<div class="blog-post">
    <div class="blog-user">
        <div class="user-photo">
            <img src="<?= $imageUrl ?>" class="photo" alt="user avatar">
        </div>
        <div class="user-info">
            <div class="user-name">
                <?= $userName ?>
            </div>
            <div class="post-time">
                <?= $postDate ?>
            </div>
        </div>
    </div>
    <div class="blog-links">
        <div class="rating-counter">
            <a href="javascript:void(0)" class="rating-up"></a>
            <div class="rating-count red">9</div>
            <a href="javascript:void(0)" class="rating-down"></a>
        </div>
        <a href="<?= Url::to(['/blog/edit/'.$model->id]) ?>" class="button-edit"></a>
    </div>
    <a href="<?= Url::to(['/blog/'.$model->id.'-'.$model->slug]) ?>" class="post-title">
        <?= $model->title ?>
    </a>
    <div class="blog-body">
        <?= $model->getShortContent() ?>
    </div>
    <div class="comment-replies">
        <a href="<?= Url::to(['/blog/'.$model->id.'-'.$model->slug, 
        '#' => 'comments']) ?>">
            <div class="toggle-text"><?= $model->comments_count ?> комментариев</div>
            <div class="toggle-icon"></div>
        </a>
    </div>
</div>
