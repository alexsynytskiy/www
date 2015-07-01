<?php
/**
 * @var $this yii\web\View
 * @var $newsDataProvider yii\data\ActiveDataProvider
**/
?>

<div class="news-block">
    <?php
    echo \yii\widgets\ListView::widget([
        'dataProvider' => $newsDataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => '@frontend/views/site/news_item',
        'summary' => '', 
    ]);
    ?>
</div>

