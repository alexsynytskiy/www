<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\VideoPost
**/

$image = $model->getAsset(\common\models\Asset::THUMBNAIL_BIG);
$imageUrl = $image->getFileUrl();

$adminLink = '';
if(Yii::$app->user->can('admin')) {
  $adminLink = '<a class="admin-view-link" target="_blank" href="/admin/video-post/update?id='.$model->id.'"></a>';
} 
?>

<div class="default-box album-preview-box">
    <?= $adminLink ?>
    <div class="image-box">
        <img src="<?= $imageUrl ?>">
    </div>
    <div class="box-content">
        <a href="<?= $model->getUrl() ?>" class="title">
            <?= $model->title ?>
        </a>
        <div class="time">
            <?= Yii::$app->formatter->asDate(strtotime($model->created_at),'d MMMM Y HH:mm') ?>
        </div>
    </div>
</div>