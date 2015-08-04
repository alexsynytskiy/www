<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $model common\models\Album
**/

$image = $model->getFrontendAsset(\common\models\Asset::THUMBNAIL_BIG);
$imageUrl = $image->getFileUrl();

$adminLink = '';
if(Yii::$app->user->can('admin')) {
  $adminLink = '<a class="admin-view-link" target="_blank" href="/admin/album/'.$model->id.'"></a>';
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
    <div class="photos-count"><?= $model->getPhotosCount() ?></div>
</div>