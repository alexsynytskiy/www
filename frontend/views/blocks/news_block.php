<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $posts Array of common\models\Post
**/
?>
<div class="news">
	<div class="header">
		<div class="title">Новости</div>
		<a href="<?= Url::to(['/site/news']) ?>">
			<div class="link-to-all-icon"></div>
			<div class="link-to-all-text">Все новости:</div>
		</a>
	</div>
	<?php foreach($posts as $post) { ?>
	<div class="message">		
		<div class="text">
			<a href="<?= $post->getUrl() ?>">
				<div class="time">
					<?= date('H:i',strtotime($post->created_at)) ?>
				</div>
				<div class="text-main">
					<?= $post->title ?>
				</div>
			</a>
			<div class="icons">
				<div class="icons-mokup">
					<?php $commentsCount = $post->getCommentsCount(); ?>
					<?php if($commentsCount > 0) { ?>
						<div class="comments-icon"></div>
						<div class="comments-count"><?= $commentsCount ?></div>
					<?php } ?>
					<?php if($post->with_video) { ?>
						<div class="video-icon"></div>
					<?php } elseif($post->with_photo) { ?>
						<div class="photo-icon"></div>
					<?php } ?>
				</div>
			</div>
		</div>		
	</div>
	<?php }	?>
	<div class="header no-border">
		<a href="<?= Url::to(['/site/news']) ?>">
			<div class="link-to-all-icon"></div>
			<div class="link-to-all-text">Все новости:</div>
		</a>
	</div>
</div>