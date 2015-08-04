<?php
use yii\helpers\Url;
use common\models\Banner;
use common\models\SiteBlock;

/**
 * @var $this yii\web\View
 * @var $posts Array of common\models\Post
 * @var $enableBanners boolean 
**/
?>
<div class="news">
	<div class="header">
		<div class="title">Новости</div>
		<a href="<?= Url::to('/news') ?>">
			<div class="link-to-all-icon"></div>
			<div class="link-to-all-text">Все новости:</div>
		</a>
	</div>
	<?php 
		$count = 0;
		foreach($posts as $post) { 
			$count++;
	?>
	<div class="message <?= $enableBanners && $count && $count % 10 == 0 ? 'border-none' : '' ?>">		
		<div class="text">
			<a href="<?= $post->getUrl() ?>">
				<div class="time">
					<?= date('H:i',strtotime($post->created_at)) ?>
				</div>
				<div class="text-main <?= $post->is_pin ? 'bold' : '' ?>">
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
	<?php
			if($enableBanners && $count && $count % 10 == 0) {
				$bannerBlock = SiteBlock::getBanner(Banner::REGION_NEWS);
		        if($bannerBlock) {
		            echo $this->render($bannerBlock['view'], isset($bannerBlock['data']) ? $bannerBlock['data'] : []);
		        }
			}
		}	
	?>
	<div class="header no-border">
		<a href="<?= Url::to('/news') ?>">
			<div class="link-to-all-icon"></div>
			<div class="link-to-all-text">Все новости:</div>
		</a>
	</div>
</div>