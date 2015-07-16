<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $videoPost common\models\VideoPost
 * @var $image common\models\Asset
 * @var $videoPost common\models\Asset
**/
Yii::$app->formatter->locale = 'ru-RU';

$editLink = '';
if(!Yii::$app->user->isGuest && Yii::$app->user->can("admin")){
    $editUrl = Url::to('/admin/video-post/update/'.$videoPost->id);
    $editLink = '<a class="edit-link" href="'.$editUrl.'">Редактировать</a>';
}
?>

<div class="post-page">
    <div class="top-block">
        <div class="date-icon"></div>
        <div class="date-text"><?= Yii::$app->formatter->asDate(strtotime($videoPost->created_at),'d MMMM Y HH:mm') ?></div>
        <div class="right">
            <div class="comments-icon"></div>
            <div class="comments-count"><?= $videoPost->getCommentsCount() ?></div>
        </div>
    </div>
    <div class="post-container">
        <div class="title"><?= $videoPost->title.$editLink ?></div>
        <?php if($video->getFileUrl()) { ?>
            <video class="post-video" src="<?= $video->getFileUrl() ?>" controls></video>
         <?php } ?>
         <div class="content">
            <?= $videoPost->content ?>
        </div>
        <div class="footer-part-top">
            <?php
                $tags = explode(',',$videoPost->cached_tag_list);
                foreach($tags as $tag) {
                    $tag = trim($tag);
                    if($tag != '') {
                        $tagSearch = str_replace('+', '-+-', $tag);
                        $tagSearch = str_replace(' ', '+', $tagSearch);
                ?>
                    <a class="tag" href="/search?t=<?= $tagSearch ?>">#<?= $tag ?></a>
            <?php } }?>
            <div class="clearfix"></div>
        </div>

        <div class="footer-part-bottom">
            <a href="#">
                <div class="vk-likes">
                    <div class="count">37</div>
                </div>
            </a>
            <a href="#">
                <div class="fb-likes">
                    <div class="count">11</div>
                </div>
            </a>

            <a href="#"><div class="button mail"></div></a>
            <a href="#"><div class="button ok"></div></a>
            <a href="#"><div class="button write"></div></a>
            <a href="#"><div class="button twitter"></div></a>
            <a href="#"><div class="button fb"></div></a>
            <a href="#"><div class="button vk"></div></a>

            <div class="clearfix"></div>
        </div>

        <div class="clearfix"></div>
    </div>
 </div>