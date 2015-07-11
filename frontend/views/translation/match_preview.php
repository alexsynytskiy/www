<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $match common\models\Match 
 * @var $matchEvents array of common\models\MatchEvent 
 * @var $teamHomePlayers array of common\models\Composition 
 * @var $teamGuestPlayers array of common\models\Composition 
**/

$stadiumName = isset($match->stadium) ? $match->stadium->name : '';
$leagueName = isset($match->league) ? $match->league->name : '';
$championshipPartName = isset($match->championshipPart) ? $match->championshipPart->name : '';

$teamHome = $match->teamHome;
$teamHomeIcon = $teamHome->getAsset();
$teamHomeIconUrl = $teamHomeIcon->getFileUrl();

$teamGuest = $match->teamGuest;
$teamGuestIcon = $teamGuest->getAsset();
$teamGuestIconUrl = $teamGuestIcon->getFileUrl();

$goalEvents = [];
foreach ($matchEvents as $event) {
    if($event->match_event_type_id == $event::GOAL) {
        $goalEvents[] = $event;
    }
}

function findEvent($event, $players) {
    foreach ($players as $player) {
        if($event->composition_id == $player->id) return true;
    }
    return false;
}

?>

<div class="match-protocol">
    <div class="top-title">
        <div class="stadium"><?= $stadiumName ?></div>
        <div class="date"><?= date('d.m.Y H:i', strtotime($match->date)) ?></div>
        <div class="clearfix"></div>
    </div>
    <div class="semi-title">
        <div class="competition"><?= $leagueName ?></div>
        <div class="tour"><?= $championshipPartName ?></div>
        <div class="clearfix"></div>
    </div>
    <div class="team">
        <div class="name house"><?= $teamHome->name ?></div>
        <?php 
            foreach ($goalEvents as $goalEvent) {
                if(findEvent($goalEvent, $teamHomePlayers)) {
                    $contract = $goalEvent->composition->contract;
                    if(isset($contract) && in_array($contract->command_id, \common\models\Team::getTeamsConstants())) {
                        $playerUrl = $contract->player->getUrl();
                    } else {
                        $playerUrl = false;
                    }
                ?>
                <div class="goal">
                    <div class="minute house"><?= $goalEvent->getTime() ?></div>
                    <?php if($playerUrl) { ?>
                        <a href="<?= $playerUrl ?>">
                    <?php } ?>
                    <div class="player house"><?= $goalEvent->composition->name ?></div>
                    <?php if($playerUrl) { ?>
                        </a>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>
                <?php }        
            }
        ?>
        
    </div>
    <div class="logo-score-block">
        <?php if($teamHomeIconUrl) { ?>
            <img src="<?= $teamHomeIconUrl ?>" class="logo-house">
        <?php } ?>
        <div class="score"><?= $match->home_goals ?> : <?= $match->guest_goals ?></div>
        <?php if($teamGuestIconUrl) { ?>
            <img src="<?= $teamGuestIconUrl ?>" class="logo-visitors">
        <?php } ?>
    </div>
    <div class="team">
        <div class="name visitors"><?= $teamGuest->name ?></div>
        <?php 
            foreach ($goalEvents as $goalEvent) {
                if(findEvent($goalEvent, $teamGuestPlayers)) {
                    $contract = $goalEvent->composition->contract;
                    if(isset($contract) && in_array($contract->command_id, \common\models\Team::getTeamsConstants())) {
                        $playerUrl = $contract->player->getUrl();
                    } else {
                        $playerUrl = false;
                    }
                ?>
                <div class="goal">
                    <div class="minute visitors"><?= $goalEvent->getTime() ?></div>
                    <?php if($playerUrl) { ?>
                        <a href="<?= $playerUrl ?>">
                    <?php } ?>
                    <div class="player visitors"><?= $goalEvent->composition->name ?></div>
                    <?php if($playerUrl) { ?>
                        </a>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>
                <?php }        
            }
        ?>
    </div>
    <div class="clearfix"></div>
</div>