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

<?php echo $this->render('@frontend/views/translation/menu', compact('match')) ?>

<?php echo $this->render('@frontend/views/translation/match_preview', compact(
        'match', 
        'matchEvents',
        'teamHomePlayers',
        'teamGuestPlayers'
    )); ?>

<div id="text-translation" class="text-translation default-box">
    <div class="box-header">
        <div class="box-title">Текстовая трансляция</div>
    </div>
    <div class="box-content">
    <?php
        foreach (array_reverse($matchEvents) as $event) {
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
      <?php }
            else {

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
                    <?php
                        if($event->match_event_type_id == $event::GOAL) {
                            echo '<div class="text goal">';
                        }
                        else {
                            echo '<div class="text">';
                        }

                        echo $event->notes ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
          <?php }
        }
    ?>

    </div>
</div>