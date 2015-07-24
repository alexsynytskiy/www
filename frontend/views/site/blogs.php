
<?php
/**
 * @var $this yii\web\View
 * @var $postsDataProvider yii\data\ActiveDataProvider
**/
?>

<div class="blogs-block">
    <?php
    echo \yii\widgets\ListView::widget([
        'dataProvider' => $postsDataProvider,
        'itemOptions' => ['class' => 'post-item'],
        'itemView' => '@frontend/views/site/blogs_item',
        'pager' => [
            'class' => \kop\y2sp\ScrollPager::className(),
            'item' => '.post-item',
            'delay' => 0,
            'noneLeftText' => '',
            'triggerOffset' => 100,
            'spinnerTemplate' => '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>',
         ],
         'summary' => '', 
    ]);
    ?>
</div>

