<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $album common\models\Album
**/

$bigImages = $album->getAssets(\common\models\Asset::THUMBNAIL_CONTENT);
$smallImages = $album->getAssets(\common\models\Asset::THUMBNAIL_SMALL);

$editLink = '';
if(!Yii::$app->user->isGuest && Yii::$app->user->can("admin")){
    $editUrl = Url::to('/admin/album/update/'.$album->id);
    $editLink = '<a class="button-edit" href="'.$editUrl.'"></a>';
}
?>

<div class="default-box album-box">
    <div class="top-block">
        <div class="date-icon"></div>
        <div class="date-text">
            <?= Yii::$app->formatter->asDate(strtotime($album->created_at),'d MMMM Y HH:mm') ?>
        </div>
        <div class="right">
            <?= $editLink ?>
            <div class="image-icon"></div>
            <div class="image-count"><?= $album->getPhotosCount() ?></div>
        </div>
    </div>
    <div class="album-container">
        <div class="title"><?= $album->title.$editLink ?></div>

        <div id="album-slider">
            <?php foreach ($bigImages as $image) { ?>
                <?php if($image->getFileUrl()) { ?>
                    <div>
                        <a href="<?= $album->getPhotoUrl($image->id) ?>">
                            <img src="<?= $image->getFileUrl() ?>" />
                        </a>
                    </div>
                <?php } ?>        
            <?php } ?>        
        </div>

        <div id="album-bx-pager">
            <?php $count = 0; ?>
            <?php foreach ($smallImages as $image) { ?>
                <?php if($image->getFileUrl()) { ?>
                    <a data-slide-index="<?= $count++ ?>" href="javascript:void()" class="pager-item">
                        <img src="<?= $image->getFileUrl() ?>" />
                    </a>
                <?php } ?>        
            <?php } ?>
        </div>

    </div>
</div>

