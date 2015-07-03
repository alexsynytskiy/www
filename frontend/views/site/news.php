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
		'itemOptions' => ['class' => 'post-item'],
		'itemView' => '@frontend/views/site/news_item',
		'pager' => [
	     	'class' => \kop\y2sp\ScrollPager::className(),
            'item' => '.post-item',
	     	'delay' => 0,
	     	// 'noneLeftText' => 'Больше нет новостей',
	     	'noneLeftText' => '',
	     	'triggerOffset' => 100,
	     	'spinnerTemplate' => '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>',
	     ],
	     'summary' => '', 
	]);
	?>
</div>

