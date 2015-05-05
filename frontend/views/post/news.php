<?php
/**
 * @var $this yii\web\View
 * @var $posts Array of common\models\Post
**/
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
			for($i = 4; $i >= 0; $i--)
			{
				$date = date('d.m.Y', time() - 60*60*24*$i);
				$class = isset($_GET['date']) && $_GET['date'] == $date ? 'active' : '';
			?>
        		<a class="o-day <?= $class ?>" href="?date=<?= $date ?>"><?= $date ?></a>
			<?php
			}	
		?>
    </div>
	<div id="top-calendar" style="display: none;">
	    <div class="header">
	        <div class="select-year">
	            <span class="slabel">Года: </span>
	            <span class="o-year active">2015</span>
	            <span class="o-year">2014</span>
	            <span class="o-year">2013</span>
	            <span class="o-year">2012</span>
	            <span class="o-year">2011</span>
	            <span class="o-year">2010</span>
	            <span class="o-year">2009</span>
	            <span class="o-year">2008</span>
	            <span class="o-year">2007</span>
	            <span class="o-year">2006</span>
	            <span class="o-year">2005</span>
	            <span class="o-year">2004</span>
	            <span class="o-year">2003</span>
	            <span class="o-year">2002</span>
	            <span class="o-year">2001</span>
	        </div>
	        <div class="current-year"><div>2015</div></div>
	    </div>
	    <div class="content"></div>
	</div>
</div>

<div class="news-block">
	<?php 
		$prevTime = 0;
		foreach($posts as $post) { 
			$currentTime = strtotime(date("Y-m-d 00:00:00", strtotime($post->created_at)));
			if(isset($_GET['date']) && $currentTime - $prevTime >= 60*60*24 ||
				!isset($_GET['date']) && $prevTime - $currentTime >= 60*60*24 ||
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
		<div class="news-comments-block">
			<div class="icon"></div>
			<div class="count"><?= rand(0,100) ?></div>
			<div class="clearfix"></div>
		</div>
	</div>
	<?php } ?>
</div>