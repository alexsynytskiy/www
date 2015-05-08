<?php
/**
 * @var $this yii\web\View
 * @var $comments array Array of common\models\Comment
 * @var $commentModel common\models\Comment
**/

/**
 * Output tree of comments
 * @param array $comments Array of Comment
 * @param int $parent_id 
 * @param int $level 
 */
function outCommentsTree($comments, $parent_id, $level) 
{
    if (isset($comments[$parent_id])) 
    { 
        foreach ($comments[$parent_id] as $comment) 
        {
            $username = $comment->user->getDisplayName();
            $avatar = $comment->user->getAsset();
            $imageUrl = $avatar->getFileUrl();

            $commentDate = Yii::$app->formatter->asDate($comment->created_at, 'dd MMMM YYYY HH:mm');

            $repliesCommentsCount = isset($comments[$comment->id]) ? count($comments[$comment->id]) : 0;
            $classRepliesCount = ($repliesCommentsCount == 0) ? 'no-replies' : '';
            $textRepliesCount = ($repliesCommentsCount == 0) ? '' : $repliesCommentsCount;
            $isReply = $parent_id == 0 ? false : true;

            $rating = 5;
            $displayType = 'comment';
            $page = 'post';

            ?>
            <div id="comment-<?= $comment->id ?>" class="comment">
                <div class="comment-user">
                    <div class="user-photo"><a href="<?= \yii\helpers\Url::to('/user/profile/'.$comment->user->id) ?>"><img src="<?=$imageUrl?>"></a></div>
                    <div class="user-info">
                        <div class="user-name"><a href="<?= \yii\helpers\Url::to('/user/profile/'.$comment->user->id) ?>"><?=$username?></a></div>
                        <div class="post-time"><?= $commentDate ?></div>
                    </div>
                </div>
                <div class="comment-links">
                    <div class="rating-counter">
                        <a href="javascript:void(0)" class="rating-up"></a>
                        <div class="rating-count <?=($isReply)?'blue':'red'?>"><?=$rating?></div>
                        <a href="javascript:void(0)" class="rating-down"></a>
                    </div>
                    <?php if($displayType == 'comment'): ?>
                        <?php if(!Yii::$app->user->isGuest) { ?>
                        <a href="javascript:void(0)" class="button-reply" title="Ответить" data-comment-id="<?= $comment->id ?>"></a>
                        <?php } ?>
                        <?php if($page == 'cabinet') { ?>
                            <a href="javascript:void(0)" class="new-replies-count <?=$classRepliesCount?>" title="Новых ответов">
                                <?=$textRepliesCount?>
                            </a>
                        <?php } ?>
                    <?php else: ?>
                        <a href="javascript:void(0)" class="button-edit" title="Изменить"></a>
                        <a href="javascript:void(0)" class="button-remove" title="Удалить"></a>
                    <?php endif; ?>
                </div>
                <?php if($displayType == 'post') { ?>
                    <a href="#" class="post-title">
                        <?php // echo $post->title; ?>
                    </a>
                <?php } ?>
                <div class="comment-body">
                    <?= $comment->getContent() ?>
                </div>
                <?php if($repliesCommentsCount > 0) { ?>
                <div class="comment-replies">
                    <a class="replies-toggle-btn toggle-button toggle-hide" data-target="comment-replies-content-<?= $comment->id ?>" href="javascript:void(0)">
                        <?php if($displayType == 'comment'): ?>
                            <div class="toggle-text"><span>Скрыть</span> ответы</div>
                        <?php else: ?>
                            <div class="toggle-text"><?=$textRepliesCount?> комментариев</div>
                        <?php endif; ?>
                        <div class="toggle-icon"></div>
                    </a>
                    <div id="comment-replies-content-<?= $comment->id ?>" class="toggle-content show">
                    <?php
                        $level++;
                        outCommentsTree($comments, $comment->id, $level);
                        $level--;
                    ?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php
            
        }
    }
}

?>
<div class="comments-block">
    <div class="header">
        <div class="title">Комментарии</div>
        <div class="help">
            <?php if(Yii::$app->user->isGuest) { ?>
            <a href="<?= \yii\helpers\Url::to(['user/login']) ?>">Войдите в систему</a>
             или 
            <a href="<?= \yii\helpers\Url::to(['user/register']) ?>">Зарегистрируйтесь</a>
            <?php } ?>
        </div>
    </div>

    <?php 
        if(!Yii::$app->user->isGuest) {
            echo $this->render('@frontend/views/forms/comment_form', compact('commentModel'));
        }
    ?>

    <?php 
        if (count($comments) > 0) {
            outCommentsTree($comments, 0, 0); 
        }
    ?>

</div>