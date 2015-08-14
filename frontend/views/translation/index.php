<?php
use yii\helpers\Url;
use common\models\MatchEventType;
/**
 * @var $this yii\web\View
 * @var $match common\models\Match 
 * @var $matchEvents array of common\models\MatchEvent
 * @var $teamHomePlayers array of common\models\Composition 
 * @var $teamGuestPlayers array of common\models\Composition 
**/
?>

<?php 
if(count($matchEvents) > 0) {
?>

<div id="text-translation" class="text-translation default-box">    
    <div class="box-content">
    <?php 
        $matchStartTime = strtotime($match->date);
        if($match->is_visible && !$match->is_finished && $matchStartTime < time()) {
    ?>
    <div class="auto-refresh">
        <div class="settings">
            <div class="text">Время автобновления:</div>
            <div class="select-refresh selectize-box">
                <select name="refresh" id="select-refresh" placeholder="Не обновлять">
                    <option value="0" selected class="data-default">Не обновлять</option>
                    <option value="30">Каждые 30 секунд</option>
                    <option value="60">Каждые 60 секунд</option>
                    <option value="120">Каждые 120 секунд</option>
                </select>
            </div>
        </div>
        <div class="actions">
            <a href="<?= Yii::$app->request->url ?>" class="button-refresh">Обновить</a>
            <div class="text timer">Обновление через <span class="time"></span> секунд</div>
        </div>
    </div>
    <?php } ?>
        <?php
            $match_events = [];
            if(!$match->is_finished) {
                $match_events = array_reverse($matchEvents);
            }
            else {
                $match_events = $matchEvents;
            }

            foreach ($match_events as $event) {
                if(trim($event->notes) == '') continue;
                if ($event->match_event_type_id == NULL) { ?>
                    <div class="message">
                        <div class="info">
                            <div class="minute"><?= $event->getTime() ?></div>
                        </div>
                        <div class="text">
                            <?= $event->notes ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
            <?php } else {
                    $eventValue = MatchEventType::find()
                        ->where([
                            'id' => $event->match_event_type_id,
                        ])
                        ->all();
                    $eventIcon = $eventValue[0]->getAsset();
                    $eventIconUrl = $eventIcon->getFileUrl();
            ?>
                    <div class="message message-with-icon">
                        <div class="info">
                            <?php if($eventIconUrl) { ?>
                                <img src="<?= $eventIconUrl ?>" class="icon">
                            <?php } ?>
                            <div class="minute"><?= $event->getTime() ?></div>
                        </div>
                        <div class="text <?php if($event->match_event_type_id == $event::GOAL ||
                                                  $event->match_event_type_id == $event::AUTOGOAL ||
                                                  $event->match_event_type_id == $event::GOAL_PENALTY ||
                                                  $event->match_event_type_id == $event::SUBSTITUTION ||
                                                  $event->match_event_type_id == $event::YELLOWCARD ||
                                                  $event->match_event_type_id == $event::REDCARD ||
                                                  $event->match_event_type_id == $event::SECONDYELLOW) {
                                                    echo 'goal';
                                                } ?>">
                            <?= $event->notes ?>
                        </div>
                    <div class="clearfix"></div>
                    </div>
              <?php }
            }
        ?>
    </div>
</div>
<?php } ?>