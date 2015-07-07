<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $model common\models\Post
**/

$avatar = $model->user->getAsset();
$image = $model->getAsset(\common\models\Asset::THUMBNAIL_CONTENT);
$imageUrl = $image->getFileUrl();
$tags = explode(',',$model->cached_tag_list);
$tagsCount = 0;
foreach ($tags as $tag) {
    if(trim($tag) != '') $tagsCount++;
}
?>

<div class="default-box blog-preview">
    <div class="user-column">
        <a href="<?= $model->user->getUrl() ?>">
            <img src="<?= $avatar->getFileUrl() ?>">
        </a>
    </div>
    <div class="data-column">
        <div class="row row-desc">
            <div class="author">
                <span class="label">Автор: </span>
                <a href="<?= $model->user->getUrl() ?>"><?= $model->user->username ?></a>
            </div>
            <div class="time">
                <?= Yii::$app->formatter->asDate(strtotime($model->created_at),'d MMMM Y HH:mm') ?>
            </div>
        </div>
        <a href="<?= $model->url ?>" class="title">
            <?= $model->title ?>
        </a>
        <div class="short-content">
            <?= $model->getShortContent() ?>
        </div>
        <?php if (!empty($imageUrl)) { ?>
            <div class="row-image">
                <img src="<?= $imageUrl ?>">
            </div>
        <?php } ?>
        <?php if($tagsCount > 0) { ?>
            <div class="row row-tags">
                <span class="label">Теги: </span>
                <?php
                    foreach($tags as $tag) {
                        $tag = trim($tag);
                        if($tag != '') {
                            $tagSearch = str_replace('+', '-+-', $tag);
                            $tagSearch = str_replace(' ', '+', $tagSearch);
                    ?>
                    <a class="tag" href="/search?t=<?= $tagSearch ?>">#<?= $tag ?></a>
                <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="row row-footer">
            <div class="rating">
                <span class="label">Рейтинг: </span>
                <span class="blue"><?= $model->getRating() ?></span>
            </div>
            <?php if($model->comments_count > 0) { ?>
                <div class="comments-count">
                    <div class="icon"></div>
                    <div class="count"><?= $model->comments_count ?></div>
                </div>
            <?php } ?>
        </div>

    </div>
</div>