<?php
use yii\helpers\Url;
/**
 * @var $this yii\web\View
 * @var $comments array Array of common\models\Comment
 * @var $commentModel common\models\Comment
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

    <?php 
        if (count($comments) > 0) {
            echo $this->render('@frontend/views/site/comments_tree', compact('comments'));
        }
    ?>

</div>