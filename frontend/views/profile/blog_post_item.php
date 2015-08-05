<?php 
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $model common\model\Post
**/

$userName = $model->user->getDisplayName();
$avatar = $model->user->getAsset();
$imageUrl = $avatar->getFileUrl();
$postDate = Yii::$app->formatter->asDate(strtotime($model->created_at), 'd MMMM Y H:m');

$rating = $model->getRating();
$ratingUpClass = '';
$ratingDownClass = '';
if(!Yii::$app->user->isGuest && Yii::$app->user->id != $model->user->id)
{
    $userRating = $model->getUserVote();
    if($userRating == 1) {
        $ratingUpClass = 'voted';
    } elseif ($userRating == -1) {
        $ratingDownClass = 'voted';
    }
} else {
    $ratingUpClass = 'disable';
    $ratingDownClass = 'disable';
}
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
            <a href="javascript:void(0)" class="rating-up <?= $ratingUpClass ?>" data-id="<?= $model->id ?>" data-type="post"></a>
            <div class="rating-count <?=($rating >= 0) ? 'blue' : 'red'?>"><?=$rating?></div>
            <a href="javascript:void(0)" class="rating-down <?= $ratingDownClass ?>" data-id="<?= $model->id ?>" data-type="post"></a>
        </div>
        <a href="<?= Url::to(['/post/edit/', 'id' => $model->id]) ?>" class="button-edit"></a>
    </div>
    <a href="<?= $model->getUrl() ?>" class="post-title">
        <?= $model->title ?>
    </a>
    <div class="blog-body">
        <?= $model->getShortContent() ?>
    </div>
    <div class="comment-replies">
        <a href="<?= $model->getUrl().'#comments' ?>">
            <div class="toggle-text"><?= $model->getCommentsCount() ?> комментариев</div>
            <div class="toggle-icon"></div>
        </a>
    </div>
</div>
