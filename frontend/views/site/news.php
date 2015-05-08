<?php
/**
 * @var $this yii\web\View
 * @var $posts Array of common\models\Post
 * @var $date string | bool Selected date
**/
$currentYear = (int)date('Y',time());
?>
<div class="date-news-search calendar">
	<div class="calendar-title">
		<div class="text">Выбрать другую дату <div>с помощью календаря: </div></div>
		<a class="toggle-button toggle-show" href="javascript:void(0)" data-target="top-calendar">
			<div class="calendar-picker">
				<div class="icon"></div>
			</div>
		</a>
	</div>
	<div class="select-day">
        <span class="slabel">Дни: </span>
		<?php
			$selectedTime = $date ? strtotime($date) : time();
			$selectedTime += 60*60*24*2;
			for($i = 4; $i >= 0; $i--)
			{
				$dateValue = date('d.m.Y', $selectedTime - 60*60*24*$i);
				$dateText = Yii::$app->formatter->asDate($selectedTime - 60*60*24*$i, 'dd MMMM');
				$class = $i == 2 ? 'active' : '';
			?>
        		<a class="o-day <?= $class ?>" href="?date=<?= $dateValue ?>"><?= $dateText ?></a>
			<?php
			}	
		?>
    </div>
	<div id="top-calendar" style="display: none;">
	    <div class="header">
	        <div class="select-year">
	            <span class="slabel">Года: </span>
				<?php for($year = $currentYear; $year >= 2001; $year--) { ?>
	            	<span class="o-year <?= ($year == $currentYear) ? 'active' : '' ?>"><?= $year ?></span>
				<?php } ?>
	        </div>
	        <div class="current-year"><div><?= $currentYear ?></div></div>
	    </div>
	    <div class="content"></div>
	</div>
	<?php 
		if($date) { 
			$this->registerJs(
				"var calendarDate = '".date('m-d-Y', strtotime($date))."';",
				\yii\web\View::POS_BEGIN,
				'my-options');
		} 
	?>
</div>

<div class="news-block">
	<?php if(count($posts) == 0) { ?>
		<div class="empty-result">По заданному запросу материалов не найдено</div>
	<?php } ?>
	<?php
		$prevTime = 0;
		foreach($posts as $post) { 
			$currentTime = strtotime(date("Y-m-d 00:00:00", strtotime($post->created_at)));
			if($date && $currentTime - $prevTime >= 60*60*24 ||
				!$date && $prevTime - $currentTime >= 60*60*24 ||
				$prevTime == 0)
			{
			?>
				<div class="date">
					<div class="line"></div>
					<div class="day-month-year"><?= date('d.m.Y',$currentTime) ?></div>
					<div class="line"></div>
					<div class="clearfix"></div>
				</div>
			<?php
				$prevTime = $currentTime;
			}
	?>
	<div class="news-post">
		<div class="time"><?= date('H:i',strtotime($post->created_at)) ?></div>
		<a href="<?= \yii\helpers\Url::to(['news/'.$post->id.'-'.$post->slug]) ?>">
			<div class="title"><?= $post->title ?></div>
		</a>
		<div class="sub-part">
			<?php
				$image = $post->getAsset(\common\models\Asset::THUMBNAIL_NEWS);
				if (!empty($image->getFileUrl())) {
			?>
			<div class="photo-img">
				<img src="<?= $image->getFileUrl() ?>">
			</div>
			<?php } ?>
			<div class="subtitle">
			<?= $post->getShortContent() ?>
			</div>
		</div>
		<?php if($post->comments_count > 0) { ?>
		<div class="news-comments-block">
			<div class="icon"></div>
			<div class="count"><?= $post->comments_count ?></div>
			<div class="clearfix"></div>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
</div>