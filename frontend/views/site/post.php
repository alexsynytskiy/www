<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $post common\models\Post
 * @var $image common\models\Asset
**/
Yii::$app->formatter->locale = 'ru-RU';

$editLink = '';
if(!Yii::$app->user->isGuest && Yii::$app->user->can("admin")){
    $editUrl = Url::to('/admin/post/update/'.$post->id);
    $editLink = '<a class="edit-link" href="'.$editUrl.'">Редактировать</a>';
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
?>

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
        <div class="date-icon"></div>
        <div class="date-text"><?= Yii::$app->formatter->asDate(strtotime($post->created_at),'d MMMM Y HH:mm') ?></div>
        <div class="right">
            <?php if($post->isBlog() && $post->user_id == Yii::$app->user->id) { ?>
            <a class="button-edit" href="<?= Url::to(['/post/edit', 'id' => $post->id]) ?>"></a>
            <?php } ?>
            <div class="comments-icon"></div>
            <div class="comments-count"><?= $post->getCommentsCount() ?></div>
        </div>
    </div>
    <div class="post-container">
        <div class="title" style="float:left;"><?= $post->title.$editLink ?></div>
        <?php
            if($post->isSelected() && $post->isBlog()) {
        ?>
                <div style="float:right;">tyuytuty</div>
        <?php
            }
        ?>
        <div class="clearfix"></div>
        <?php if(!empty($image->getFileUrl())) { ?>
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