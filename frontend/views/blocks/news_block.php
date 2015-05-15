<?php
/**
 * @var $this yii\web\View
 * @var $posts Array of common\models\Post
**/
?>
<div class="news">
	<div class="header">
		<div class="title">Новости</div>
		<a href="<?= \yii\helpers\Url::to(['/site/news']) ?>">
			<div class="link-to-all-icon"></div>
			<div class="link-to-all-text">Все новости:</div>
		</a>
	</div>
	<?php foreach($posts as $post) { ?>
	<div class="message">		
		<div class="text">
			<a href="<?= \yii\helpers\Url::to(['/news/'.$post->id.'-'.$post->slug]) ?>">
				<div class="time">
					<?= date('H:i',strtotime($post->created_at)) ?>
				</div>
				<div class="text-main">
					<?= $post->title ?>
				</div>
			</a>
			<div class="icons">
				<div class="icons-mokup">
					<?php if($post->comments_count > 0) { ?>
						<div class="comments-icon"></div>
						<div class="comments-count"><?= $post->comments_count ?></div>
					<?php } ?>
					<?php if($post->is_video) { ?>
						<div class="video-icon"></div>
					<?php } ?>
				</div>
			</div>
		</div>		
	</div>
	<?php }	?>
	<div class="header no-border">
		<a href="<?= \yii\helpers\Url::to(['/site/news']) ?>">
			<div class="link-to-all-icon"></div>
			<div class="link-to-all-text">Все новости:</div>
		</a>
	</div>
</div>