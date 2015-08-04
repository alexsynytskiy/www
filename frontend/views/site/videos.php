<?php
use yii\web\JsExpression;


/**
 * @var $this yii\web\View
 * @var $videosDataProvider yii\data\ActiveDataProvider
 * @var $emptyText string
**/

$emptyText = !isset($emptyText) ? 'Видеозаписей не найдено' : $emptyText;
?>

<div class="videos-container">
    <?php
    echo \yii\widgets\ListView::widget([
        'dataProvider' => $videosDataProvider,
        'itemOptions' => ['class' => 'video-item'],
        'itemView' => '@frontend/views/site/videos_item',
        'pager' => [
            'class' => \kop\y2sp\ScrollPager::className(),
            'item' => '.video-item',
            'delay' => 0,
            'noneLeftText' => '',
            'triggerOffset' => 100,
            'spinnerTemplate' => '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>',
            'eventOnRendered' => new JsExpression("function(items){
                $(items).ready(function(){
                    $('.videos-container').indyMasonry('_newElement');
                });
            }"),
         ],
         'summary' => '', 
         'emptyText' => $emptyText,
    ]);
    ?>
</div>
