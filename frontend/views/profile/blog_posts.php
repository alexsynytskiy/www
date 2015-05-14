<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $blogPostsDataProvider yii\data\ActiveDataProvider
**/
?>
<div class="blog-posts">
    <?php if(count($blogPostsDataProvider->getModels()) == 0) { ?>
        <div class="empty">
            Тут будут отображаться записи Вашего блога
            <div>Нажмите на иконку, и сделайте первый пост</div>
        </div>
        <a href="<?= Url::to(['/post/add']) ?>"><div class="icon"></div></a>
        <div class="clearfix"></div>
    <?php } else { 
        echo \yii\widgets\ListView::widget([
            'dataProvider' => $blogPostsDataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => '@frontend/views/profile/blog_post_item',
            'summary' => '', 
        ]);
    } ?>
</div>