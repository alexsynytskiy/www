<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var $comments array Array of common\models\Comment
 * @var $commentForm common\models\Comment
 * @var $pagination 
**/
?>
<div id="comments" class="comments-block <?= Yii::$app->user->isGuest && !count($comments) ? 'small' : '' ?>">
    <div class="header">
        <div class="title">Комментарии</div>
        <div class="help">
            <?php if(Yii::$app->user->isGuest) { ?>
            <a href="<?= Url::to(['user/login']) ?>">Войдите в систему</a>
             или 
            <a href="<?= Url::to(['user/register']) ?>">Зарегистрируйтесь</a>
            <?php } ?>
        </div>
    </div>

    <?php 
        if(!Yii::$app->user->isGuest) {
            $user = Yii::$app->user;
            if($user->can('comment')) {
                echo $this->render('@frontend/views/forms/comment_form', compact('commentForm'));
            } else {
                echo '<div class="comment-error">Вы не можете отправлять комментарии. Ваш профиль забанен.</div>';
            }
        }
    ?>
    
    <div class="comments-container">
    <?php 
        Pjax::begin(['id' => 'comments-container']);
        echo $this->render('@frontend/views/site/comments_tree', compact('comments'));
        Pjax::end();
        echo \kop\y2sp\ScrollPager::widget([
            'pagination' => $pagination,
            'container' => '#comments-container',
            'item' => '.lvl-one',
            'delay' => 0,
            'noneLeftText' => '',
            'triggerOffset' => 500,
            'spinnerTemplate' => '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>',
        ]);
    ?>
    </div>
</div>