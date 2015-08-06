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
$uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$site_logo = 'http://' . $_SERVER['HTTP_HOST'] . '/images/main_logo.svg';
$site_title = $videoPost->title;
?>

<meta property="og:site_name" content="Динамомания"/>
<meta property="og:title" content="<?= $site_title ?>"/>
<meta property="og:url" content="<?= $uri ?>"/>
<meta property="og:type" content="website"/>
<meta property="og:image" content="<?= $site_logo ?>"/>
<meta itemprop="name" content="<?= $site_title ?>"/>
<meta itemprop="url" content="<?= $uri ?>"/>
<meta itemprop="thumbnailUrl" content="<?= $site_logo ?>"/>
<link rel="image_src" href="<?= $site_logo ?>" />
<meta itemprop="image" content="<?= $site_logo ?>"/>
<meta name="twitter:title" content="<?= $site_title ?>"/>
<meta name="twitter:image" content="<?= $site_logo ?>"/>
<meta name="twitter:url" content="<?= $uri ?>"/>
<meta name="twitter:card" content="summary"/>

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
            <div class="vk-like-box">
                <!-- Put this script tag to the <head> of your page -->
                <script type="text/javascript" src="//vk.com/js/api/openapi.js?116"></script>
                <script type="text/javascript">
                    VK.init({apiId: 5020969, onlyWidgets: true});
                </script>
                <!-- Put this div tag to the place, where the Like block will be -->
                <div id="vk_like"></div>
                <script type="text/javascript">
                    VK.Widgets.Like("vk_like", {type: "button", height: 24});
                </script>
            </div>
            <div class="fb-like-box">
                <div id="fb-root"></div>
                <script>(function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.4&appId=1534459160107015";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                <div class="fb-like" data-width="100" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
            </div>

            <a target="_blank" href="http://twitter.com/share?url=<?= $uri ?>&text=<?= $site_title ?>"><div class="button twitter"></div></a>
            <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?= $uri ?>&t=<?= $site_title ?>&src=sp"><div class="button fb"></div></a>
            <a target="_blank" href="http://vkontakte.ru/share.php?url=<?= $uri ?>"><div class="button vk"></div></a>

            <div class="clearfix"></div>
        </div>

        <div class="clearfix"></div>
    </div>
 </div>