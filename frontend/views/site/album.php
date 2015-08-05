<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $album common\models\Album
 * @var $bigImages array of common\models\Asset
 * @var $smallImages array of common\models\Asset
 * @var $imageCount int 
**/

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

        <div class="bxslider-main">
            <?php if(count($bigImages)) { ?>
            <div id="album-slider" data-album-id="<?= $album->id ?>" data-max-count="<?= $imageCount ?>">
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
            <?php } ?>        
        </div>

        <div class="bxslider-thumbnails">
            <?php if(count($smallImages)) { ?>
            <div id="album-bx-pager">
                <?php $count = 0; ?>
                <?php foreach ($smallImages as $image) { ?>
                    <?php if($image->getFileUrl()) { ?>
                        <a data-slide-index="<?= $count++ ?>" href="javascript:void(0)" class="pager-item">
                            <img src="<?= $image->getFileUrl() ?>" />
                        </a>
                    <?php } ?>        
                <?php } ?>
            </div>
            <?php } ?>
        </div>

    </div>
</div>

