<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;

/**
 * @var $this yii\web\View
 * @var $comments Array of common\models\Comment
 * @var $commentForm common\models\CommentForm 
 * @var $pagination yii\data\Pagination 
**/
$showComments = isset($_GET['cpage']) ? true : false;
?>
<div class="cabinet-comments">
    <div class="box-caption">
        <div class="title">
            Ваш Кабинет
        </div>
        <?php if(count($comments) > 0) { ?> 
            <a id="comments-toggle-btn" class="toggle-button toggle-<?= $showComments ? 'hide' : 'show' ?>" data-target="comments-content" href="javascript:void(0)">
                <div class="toggle-text"><span><?= $showComments ? 'Скрыть' : 'Показать' ?></span> комментарии</div>
                <div class="toggle-icon"></div>
            </a>
        <?php } ?>
    </div>
    <div id="comments-content" class="toggle-content <?= $showComments ? 'visible' : '' ?>">
        <div class="comments-status">
            <div class="new-comments">
                <!-- <span class="status-label">Новых ответов</span> -->
                <!-- <span class="status-alue">18</span> -->
            </div>
            <div class="amount-comments">
                <span class="status-label">Всего комментариев</span>
                <span class="status-value"><?= $pagination->totalCount ?></span>
            </div>
        </div>
        <?php 

        echo $this->render('@frontend/views/forms/comment_form', compact('commentForm'));
        
        $options = [
            'showReplies' => false,
            'showReplyButton' => true,
            'postID' => true,
        ];

        \yii\widgets\Pjax::begin(['id' => 'comments-container']);
        echo $this->render('@frontend/views/site/comments_tree', compact('comments', 'options'));
        echo LinkPager::widget([
            'pagination' => $pagination,
        ]);
        \yii\widgets\Pjax::end();

        ?>
    </div>
</div>

