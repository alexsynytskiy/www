<?php
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\widgets\LinkPager;

/**
 * @var $this yii\web\View
 * @var $commentsData Array
**/
$showComments = isset($_GET['cpage']) ? true : false;
?>
<div class="cabinet-comments">
    <div class="box-caption">
        <div class="title">
            Ваш Кабинет
        </div>
        <a id="comments-toggle-btn" class="toggle-button toggle-<?= $showComments ? 'show' : 'hide' ?>" data-target="comments-content" href="javascript:void(0)">
            <div class="toggle-text"><span><?= $showComments ? 'Показать' : 'Скрыть' ?></span> комментарии</div>
            <div class="toggle-icon"></div>
        </a>
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
        
        \yii\widgets\Pjax::begin();
        echo $this->render('@frontend/views/site/comments_tree', compact('comments'));
        echo LinkPager::widget([
            'pagination' => $pagination,
        ]);
        \yii\widgets\Pjax::end();

        ?>
    </div>
</div>

