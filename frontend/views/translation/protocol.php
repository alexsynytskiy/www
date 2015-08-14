<?php
use yii\helpers\Url;
/**
 * @var $this yii\web\View
 * @var $match common\models\Match 
 * @var $matchEvents array of common\models\MatchEvent
 * @var $teamHomePlayers array of common\models\Composition 
 * @var $teamGuestPlayers array of common\models\Composition 
**/
?>

<?php echo $this->render('@frontend/views/translation/match_preview', compact(
        'match', 
        'matchEvents',
        'teamHomePlayers',
        'teamGuestPlayers'
    ));

$substitutions = [];
$yellowCards = [];
$redCards = [];
$goalEvents = [];

foreach ($matchEvents as $event) {
    if($event->match_event_type_id == $event::SUBSTITUTION) {
        $substitutions[] = $event;
    }
    elseif($event->match_event_type_id == $event::YELLOWCARD) {
        $yellowCards[] = $event;
    }
    elseif($event->match_event_type_id == $event::REDCARD || $event->match_event_type_id == $event::SECONDYELLOW) {
        $redCards[] = $event;
    }
    elseif(in_array($event->match_event_type_id, $event::getGoalTypes())) {
        $goalEvents[] = $event;
    }
}

function findEventSquad($event, $player) {
    if($event->composition_id == $player->id || $event->substitution_id == $player->id) {
        return true;
    }
    return false;
}

function renderSquad($team, $isBasis, $substitutions, $yellowCards, $redCards, $goalEvents) {
    $finalBlockSquad = "";

    if($team != NULL) {
        $finalBlockSquad .= '<div class="team team-a">';
            foreach ($team as $player) {
                if($player->is_basis == $isBasis) {   
                    $playerUrl = $player->contract->player->getUrl();
                    $finalBlockSquad .= '<div class="player">
                        <div class="dest">'.$player->contract->amplua->abr.'</div>
                        <div class="number">'.$player->number.'</div>
                        <div class="desc">';
                        $finalBlockSquad .= '<a href="'.$playerUrl.'"><div class="name">'.
                            $player->contract->player->name.
                        '</div></a>';   
                        foreach ($substitutions as $substitution) {
                            if( findEventSquad($substitution, $player) ) {
                                $finalBlockSquad .= '<div class="replacement"></div>
                                <div class="time">'.$substitution->getTime().'</div>';
                            }
                        }
                        foreach ($yellowCards as $yellowCard) {
                            if( findEventSquad($yellowCard, $player) ) {
                                $finalBlockSquad .= '<div class="yellow-card"></div>
                                <div class="time">'.$yellowCard->getTime().'</div>';
                                break;
                            }
                        }
                        foreach ($redCards as $redCard) {
                            if( findEventSquad($redCard, $player) ) {
                                $finalBlockSquad .= '<div class="red-card"></div>
                                <div class="time">'.$redCard->getTime().'</div>';
                                break;
                            }
                        }
                        foreach ($goalEvents as $goal) {
                            if( findEventSquad($goal, $player) ) {
                                $goalType = $goal->match_event_type_id == $goal::AUTOGOAL ? 'autogoal' : 'goal';
                                $finalBlockSquad .= '<div class="'.$goalType.'-event"></div>
                                    <div class="time">'.$goal->getTime().'</div>';
                                break;
                            }
                        }
                    $finalBlockSquad .= '</div></div>';
                }
            }
        $finalBlockSquad .= '</div>';
    }
    echo $finalBlockSquad;
}

function renderStatistics($homeValue, $visitorsValue, $t) {
    $finalBlockStatistics = "";
    $house_value = $homeValue;
    $visitors_value = $visitorsValue;
    $title = $t;
    $width = 590;

    $average = $house_value + $visitors_value;

    if(($house_value == 0 && $visitors_value != 0) ||($house_value != 0 && $visitors_value == 0)) {
        $width = 595;
    }

    if ($average == 0) {
        $house_width = $visitors_width = $width/2;
    }
    else {
        $house_width = ($width / $average) * $house_value;
        $visitors_width = ($width / $average) * $visitors_value;
    }

    if($title == "Владение мячом") {
        $house_value = $house_value."%";
        $visitors_value = $visitors_value."%";
    }

    $finalBlockStatistics .= '<div class="parameter">
        <div class="header">
            <div class="house-value">'.$house_value.'</div>
            <div class="parameter-name">'.$title.'</div>
            <div class="visitors-value">'.$visitors_value.'</div>
            <div class="clearfix"></div>
        </div>
        <div class="stripes">
            <div class="home" style="width:'.$house_width.'px;"></div>
            <div class="visitors" style="width:'.$visitors_width.'px;"></div>
            <div class="clearfix"></div>
        </div>
    </div>';

    echo $finalBlockStatistics;
}

if($match->arbiterMain != NULL || 
   $match->arbiterAssistant1 != NULL || 
   $match->arbiterAssistant2 != NULL || 
   $match->arbiterAssistant3 != NULL || 
   $match->arbiterAssistant4 != NULL) {
?>

<div class="match-arbiters default-box">
    <div class="box-header">
        <div class="box-title">Арбитры</div>
    </div>
    <div class="box-content">
        <div class="main-arbiter">
        <?php 
            if($match->arbiterMain != NULL) { ?>
                <div class="column-title">Основной арбитр:</div>
                <div class="name"><?= $match->arbiterMain->name ?></div>
        <?php 
            } 
        ?>
        </div>
        <div class="assist-arbiter">
            <?php
                if($match->arbiterAssistant1 != NULL && $match->arbiterAssistant2 != NULL) { ?>
                    <div class="column-title">Лайнсмены:</div>
                    <div class="name"><?= $match->arbiterAssistant1->name ?></div>
                    <div class="name"><?= $match->arbiterAssistant2->name ?></div>
            <?php }
                if($match->arbiterAssistant3 != NULL && $match->arbiterAssistant4 != NULL) { ?>
                    <div class="column-title">За воротами:</div>
                    <div class="name"><?= $match->arbiterAssistant3->name ?></div>
                    <div class="name"><?= $match->arbiterAssistant4->name ?></div>
            <?php 
                }
            ?>
        </div>
        <div class="reserv-arbiter">
        <?php 
            if($match->arbiterReserve != NULL) { ?>
                <div class="column-title">Резервный арбитр:</div>
                <div class="name"><?= $match->arbiterReserve->name ?></div>
        <?php 
            } 
        ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<?php } 

if(count($teamHomePlayers) > 0 || 
   count($teamGuestPlayers) > 0) {
?>

<div class="first-team default-box">
    <div class="box-header">
        <div class="box-title">Составы команд</div>
    </div>
    <div class="box-content" style="padding: 0;">
        <!-- start team-a -->
        <?php
            renderSquad($teamHomePlayers, 1, $substitutions, $yellowCards, $redCards, $goalEvents);
        ?>
        
        <div class="delimiter"></div>
        <!-- start team-b -->
        <?php
            renderSquad($teamGuestPlayers, 1, $substitutions, $yellowCards, $redCards, $goalEvents);
        ?>
    </div>
</div>

<div class="first-team default-box">
    <div class="box-header">
        <div class="box-title">Запасные</div>
    </div>
    <div class="box-content" style="padding: 0;">
        <!-- start team-a -->
        <?php
            renderSquad($teamHomePlayers, 0, $substitutions, $yellowCards, $redCards, $goalEvents);
        ?>

        <div class="delimiter"></div>
        <!-- start team-b -->
        <?php
            renderSquad($teamGuestPlayers, 0, $substitutions, $yellowCards, $redCards, $goalEvents);
        ?>
    </div>
</div>

<?php } 

if(isset($match->home_goals) ||
   isset($match->home_ball_possession) ||
   isset($match->home_shots) ||
   isset($match->home_shots_in) ||
   isset($match->home_offsides) ||
   isset($match->home_corners) ||
   isset($match->home_fouls) ||
   isset($match->home_yellow_cards) ||
   isset($match->home_red_cards)) {
?>

<div class="match-statistics default-box">
    <div class="box-header">
        <div class="box-title">Статистика матча</div>
    </div>
    <div class="box-content">
        <div class="statistics">
            <div class="teams">
                <div class="home"><?= $match->teamHome->name ?></div>
                <div class="visitors"><?= $match->teamGuest->name ?></div>
                <div class="clearfix"></div>
            </div>
            <?php
                if(isset($match->home_goals) && isset($match->guest_goals)) {
                    renderStatistics($match->home_goals, $match->guest_goals,"Голы");
                }

                if(isset($match->home_ball_possession) && isset($match->guest_ball_possession)) {
                    renderStatistics($match->home_ball_possession, $match->guest_ball_possession,"Владение мячом");

                }

                if(isset($match->home_shots) && isset($match->guest_shots)) {
                    renderStatistics($match->home_shots, $match->guest_shots,"Удары в сторону ворот");
                }

                if(isset($match->home_shots_in) && isset($match->guest_shots_in)) {
                    renderStatistics($match->home_shots_in, $match->guest_shots_in,"Удары по воротам");
                }

                if(isset($match->home_offsides) && isset($match->guest_offsides)) {
                    renderStatistics($match->home_offsides, $match->guest_offsides,"Офсайды");
                }

                if(isset($match->home_corners) && isset($match->guest_corners)) {
                    renderStatistics($match->home_corners, $match->guest_corners,"Угловые");
                }

                if(isset($match->home_fouls) && isset($match->guest_fouls)) {
                    renderStatistics($match->home_fouls, $match->guest_fouls,"Фолы");
                }

                if(isset($match->home_yellow_cards) && isset($match->guest_yellow_cards)) {
                    renderStatistics($match->home_yellow_cards, $match->guest_yellow_cards,"Жёлтые карточки");
                }

                if(isset($match->home_red_cards) && isset($match->guest_red_cards)) {
                    renderStatistics($match->home_red_cards, $match->guest_red_cards,"Красные карточки");
                }
            ?>
        </div>
    </div>
</div>
<?php } ?>