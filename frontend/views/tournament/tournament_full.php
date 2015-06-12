<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $tournamentData array Array of common\models\Tournament 
**/
?>

<div id="scoreboard-full" class="scoreboard-full default-box">
    <div class="box-header">
        <div class="box-title">Турнирная таблица</div>
    </div>
    <div class="box-content">
        <table class="default-table">
            <thead>
                <tr>
                    <td class="status"></td>
                    <th class="num"></th>
                    <th class="club-icon"></th>
                    <th class="team">Команда</th>
                    <th class="games">Игры</th>
                    <th class="wins">Победы</th>
                    <th class="draws">Ничья</th>
                    <th class="losing">Поражение</th>
                    <th class="balls">Мячи</th>
                    <th class="scores">Очки</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 0;
                foreach($tournamentData as $tournament) { 
                    $count++;
                    $team = $tournament->team;
                    if($team->getAsset()){
                        $teamIconUrl = $team->getAsset()->getFileUrl();
                    } else $teamIconUrl = false;
                    $teamStatus = ($count < 3) ? 'green' : '';
                    $teamStatus = ($count == 3 || $count == 4) ? 'yellow' : $teamStatus;
                    $teamStatus = ($count == count($tournamentData)) ? 'red' : $teamStatus;
                    ?>
                <tr>
                    <td class="status <?= $teamStatus ?>"></td>
                    <td class="num"><?= $count ?></td>
                    <td class="club-icon">
                        <?php if($teamIconUrl) { ?>
                            <img src="<?= $teamIconUrl ?>">
                        <?php } ?>
                    </td>
                    <td class="team"><?= $team->name ?></td>
                    <td class="games"><?= $tournament->played ?></td>
                    <td class="wins"><?= $tournament->won ?></td>
                    <td class="draws"><?= $tournament->draw ?></td>
                    <td class="losing"><?= $tournament->lost ?></td>
                    <td class="balls"><?= $tournament->goals_for ?>-<?= $tournament->goals_against ?></td>
                    <td class="scores"><?= $tournament->points ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>