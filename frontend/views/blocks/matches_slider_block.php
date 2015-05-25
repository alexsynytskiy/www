<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $matches Array of common\models\Match
**/
?>

<div class="top-matches-slider">
	<ul>
	<?php foreach ($matches as $match) { ?>	
		<li>
			<div class="main-part">
				<div class="team no-border">
					<div class="name"><?= $match->commandHome->name ?></div>
					<div class="goals"><?= $match->home_goals ?></div>
				</div>
				<div class="small-line"></div>
				<div class="team">
					<div class="name"><?= $match->commandGuest->name ?></div>
					<div class="goals"><?= $match->guest_goals ?></div>
				</div>
				<div class="intro">
					<div class="match-info"><?= $match->getTournamentName() ?>
						<div><?= date("d.m.Y H:i", strtotime($match->date)) ?></div>
					</div>
					<a href="#">
						<div class="translation-link">
							<div class="translation-icon"></div>
							<div class="translation-title">Все о Матче:</div>
						</div>				
					</a>
				</div>
			</div>
		</li>
	<?php } 
//<?= $match->championship ?>
	?>	
	</ul>
</div>

