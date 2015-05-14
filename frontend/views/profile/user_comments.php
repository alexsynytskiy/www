<?php
use yii\helpers\Url;
use yii\data\ArrayDataProvider;

/**
 * @var $this yii\web\View
 * @var $commentsData Array
**/
?>
<div class="cabinet-comments">
    <div class="box-caption">
        <div class="title">
            Ваш Кабинет
        </div>
        <a id="comments-toggle-btn" class="toggle-button toggle-show" data-target="comments-content" href="javascript:void(0)">
            <div class="toggle-text"><span>Показать</span> комментарии</div>
            <div class="toggle-icon"></div>
        </a>
    </div>
    <div id="comments-content" class="toggle-content">
        <div class="comments-status">
            <div class="new-comments">
                <span class="status-label">Новых ответов</span>
                <span class="status-alue">18</span>
            </div>
            <div class="amount-comments">
                <span class="status-label">Всего комментариев</span>
                <span class="status-value">98</span>
            </div>
        </div>
        <div class="comment-theme">
            <!-- <div class="theme-label">Комментарии по теме:</div>
            <div class="theme-link">
                <a href="#">Шовковский: "Сделали нужные выводы, и теперь должны сыграть лучше"</a>
            </div> -->
            <?php 
            
            echo \yii\widgets\ListView::widget([
                'dataProvider' => $commentsDataProvider,
                'itemOptions' => ['class' => 'item'],
                'itemView' => '@frontend/views/site/comments_tree',
                'summary' => '', 
            ]);

         
            ?>
        </div>
    </div>
</div>

