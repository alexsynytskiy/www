<?php
use yii\web\JsExpression;


/**
 * @var $this yii\web\View
 * @var $albumsDataProvider yii\data\ActiveDataProvider
**/
?>

<div class="albums-container">
    <?php
    echo \yii\widgets\ListView::widget([
        'dataProvider' => $albumsDataProvider,
        'itemOptions' => ['class' => 'album-item'],
        'itemView' => '@frontend/views/site/photos_item',
        'pager' => [
            'class' => \kop\y2sp\ScrollPager::className(),
            'item' => '.album-item',
            'delay' => 0,
            'noneLeftText' => '',
            'triggerOffset' => 100,
            'spinnerTemplate' => '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>',
            'eventOnRendered' => new JsExpression("function(items){
                $(items).ready(function(){
                    $('.albums-container').indyMasonry('_newElement');
                });
            }"),
         ],
         'summary' => '', 
    ]);
    ?>
</div>
