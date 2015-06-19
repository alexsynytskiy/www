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
        <div class="message message-with-icon">
            <div class="info">
                <div class="icon"></div>
                <div class="minute">5"</div>
            </div>
            <div class="text goal">
                ГОООООООООЛЛЛЛ! Ярмоленко забиваеееет!
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="message message-with-icon">
            <div class="info">
                <div class="icon"></div>
                <div class="minute">3"</div>
            </div>
            <div class="text">
                Азеведо опасно простреливал с левого фланга, но Гоменюк на нее не откликнулся. Азеведо снова прорывается по флангу, но Вида в подкате выбивает мяч.
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="message">
            <div class="info">
                <div class="minute">2"</div>
            </div>
            <div class="text">
                Безус навешивает с левого фланга, но Азеведо опережает Виду в борьбе за мяч
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="message message-with-icon">
            <div class="info">
                <div class="icon"></div>
                <div class="minute">0"</div>
            </div>
            <div class="text">
                Игра началась!
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="message" style="border:none;">
            <div class="info">
            </div>
            <div class="text">
                Стали известны составы команд
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>