<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $post common\models\Post
 * @var $image common\models\Asset
**/
Yii::$app->formatter->locale = 'ru-RU';

$adminLink = '';
if(Yii::$app->user->can('admin')) {
  $adminLink = '<a class="admin-view-link" target="_blank" href="/admin/post/update/'.$post->id.'"></a>';
} 

if($post->isBlog()) {
    $rating = $post->getRating();
    $ratingUpClass = '';
    $ratingDownClass = '';
    if(!Yii::$app->user->isGuest && Yii::$app->user->id != $post->user->id)
    {
        $userRating = $post->getUserVote();
        if($userRating == 1) {
            $ratingUpClass = 'voted';
        } elseif ($userRating == -1) {
            $ratingDownClass = 'voted';
        }
    } else {
        $ratingUpClass = 'disable';
        $ratingDownClass = 'disable';
    }
    $ratingType = \common\models\Vote::VOTEABLE_POST;
    $avatar = $post->user->getAsset();
    $imageUrl = $avatar->getFileUrl();
    $userName = $post->user->getDisplayName();
    $createTime = date('d.m.Y', strtotime($post->user->create_time));
    $loginTime = date('d.m.Y', strtotime($post->user->login_time));
    $profile = $post->user->profile;
}
$uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$site_logo = 'http://' . $_SERVER['HTTP_HOST'] . '/images/main_logo.svg';
$site_title = $post->title;

$commentsCount = $post->getCommentsCount();
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

<?php if($post->isBlog()) { ?>
<div class="profile-box">
    <div class="top-part ">
        <img src="<?= $imageUrl ?>" class="photo">
        <div class="info-about-user">
            <div class="user-name">Автор: <span><?= $userName ?></span></div>
            <div class="user-date">Зарегистрирован: <span><?= $createTime ?></span></div>
            <div class="user-date">Последний визит: <span><?= $loginTime ?></span></div>
        </div>
    </div>
   
    <?php if(empty($profile->description)) { ?>
    <div class="empty-history">
    </div>
    <?php } else { ?>
    <div class="description">
        <?= $profile->description ?>
    </div>
    <?php } ?>
</div>
<?php } ?>

<div class="post-page">
    <div class="top-block">
        <?php if($post->isSelected() && $post->isBlog()) { ?>
            <div class="popular"></div>
        <?php } ?>
        <div class="date-icon"></div>
        <div class="date-text"><?= Yii::$app->formatter->asDate(strtotime($post->created_at),'d MMMM Y HH:mm') ?></div>
        <div class="right">
            <?= $adminLink ?>
            <?php if($post->isBlog() && $post->user_id == Yii::$app->user->id) { ?>
                <a class="button-edit" href="<?= Url::to(['/post/edit', 'id' => $post->id]) ?>"></a>
            <?php } ?>
            <?php if($commentsCount > 0) { ?>
                <div class="comments-icon"></div>
                <div class="comments-count"><?= $commentsCount ?></div>
            <?php } ?>
        </div>
    </div>
    <div class="post-container">
        <div class="title"><?= $post->title ?></div>
        <?php if($image->getFileUrl()) { ?>
            <img class="post-image" src="<?= $image->getFileUrl() ?>">
         <?php } ?>
         <div class="content">
            <?= $post->content ?>
        </div>
        <div class="footer-part-top">
            <?php if(!empty($post->source_title)) { ?>
                <div class="source">Источник:</div>
                <a class="source-link" href="<?= $post->source_url ?>"><?= $post->source_title ?></a>
            <?php } ?>
            <?php
                $tags = explode(',',$post->cached_tag_list);
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
<!--            <a href="#">-->
<!--                <div class="vk-likes">-->
<!--                    <div class="count">37</div>-->
<!--                </div>-->
<!--            </a>-->
<!--            <a href="#">-->
<!--                <div class="fb-likes">-->
<!--                    <div class="count">11</div>-->
<!--                </div>-->
<!--            </a>-->
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


            <?php if($post->isBlog()) { ?>
                <div class="rating-counter">
                    <a href="javascript:void(0)" 
                        class="rating-up <?= $ratingUpClass ?>" 
                        data-id="<?= $post->id ?>" 
                        data-type="<?= $ratingType ?>"></a>
                    <div class="rating-count <?= ($rating >= 0) ? 'blue' : 'red' ?>"><?= $rating ?></div>
                    <a href="javascript:void(0)" 
                        class="rating-down <?= $ratingDownClass ?>" 
                        data-id="<?= $post->id ?>" 
                        data-type="<?= $ratingType ?>"></a>
                </div>
            <?php } ?>
            
            <a target="_blank" href="http://twitter.com/share?url=<?= $uri ?>&text=<?= $site_title ?>"><div class="button twitter"></div></a>
            <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?= $uri ?>&t=<?= $site_title ?>&src=sp"><div class="button fb"></div></a>
            <a target="_blank" href="http://vkontakte.ru/share.php?url=<?= $uri ?>"><div class="button vk"></div></a>

            <div class="clearfix"></div>
        </div>

        <div class="clearfix"></div>
    </div>
 </div>