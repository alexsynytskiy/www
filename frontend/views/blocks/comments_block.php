<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var $this yii\web\View
 * @var $comments array Array of common\models\Comment
 * @var $commentModel common\models\Comment
 * @var $pagination 
**/
?>
<div id="comments" class="comments-block">
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
            echo $this->render('@frontend/views/forms/comment_form', compact('commentModel'));
        }
    ?>

    <div class="comments-container">
    <?php 
        echo $this->render('@frontend/views/site/comments_tree', compact('comments'));
        echo \kop\y2sp\ScrollPager::widget([
            'pagination' => $pagination,
            'container' => '.comments-container',
            'item' => '.lvl-one',
            'delay' => 0,
            'noneLeftText' => '',
            'triggerOffset' => 500,
            'spinnerTemplate' => '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>',
        ]);
    ?>
    </div>
</div>