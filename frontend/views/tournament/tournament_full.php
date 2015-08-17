<?php
use yii\helpers\Url;
use common\models\TournamentSettings;
use common\models\Season;

/**
 * @var $this yii\web\View
 * @var $tournamentData array Array of common\models\Tournament 
 * @var $championshipsData array Array of available championships
 * @var $seasonsData array Array of available seasons
**/

$tournamentTableSettings = TournamentSettings::tableName();
$seasonTable = Season::tableName();

$currentSeason = array_values($seasonsData)[0];
$currentSeasonValue = $currentSeason->value;

foreach ($seasonsData as $season) {
    if ($season->active) {
        $currentSeasonValue = $season->value;
    }
}

$settings = TournamentSettings::find()
    ->where([
                'season_id' => $currentSeasonValue,
            ])
    ->one();


$positions = preg_replace('/\s+/', '', $settings->cl_positions);
$ECLpositionsArray = explode(",", $positions);

$positions = preg_replace('/\s+/', '', $settings->el_positions);
$ELpositionsArray = explode(",", $positions);

$positions = preg_replace('/\s+/', '', $settings->reduction_positions);
$reductionPositionsArray = explode(",", $positions);

$priorityCriteria = [];
$count = 0;

if (isset($settings->win_weight)) {
    $priorityCriteria[$count]['value'] = $settings->win_weight;
    $priorityCriteria[$count]['type'] = "won";
    $count++;
}

if (isset($settings->draw_weight)) {
    $priorityCriteria[$count]['value'] = $settings->draw_weight;
    $priorityCriteria[$count]['type'] = "draw";
    $count++;
}

if (isset($settings->defeat_weight)) {
    $priorityCriteria[$count]['value'] = $settings->defeat_weight;
    $priorityCriteria[$count]['type'] = "lost";
    $count++;
}

if (isset($settings->scored_missed_weight)) {
    $priorityCriteria[$count]['value'] = $settings->scored_missed_weight;
    $priorityCriteria[$count]['type'] = "scored_missed";
    $count++;
}

if (isset($settings->goal_scored_weight)) {
    $priorityCriteria[$count]['value'] = $settings->goal_scored_weight;
    $priorityCriteria[$count]['type'] = "scored";
    $count++;
}

if (isset($settings->goal_missed_weight)) {
    $priorityCriteria[$count]['value'] = $settings->goal_missed_weight;
    $priorityCriteria[$count]['type'] = "missed";
    $count++;
}

for ($i = 0; $i < count($priorityCriteria) - 1; $i++) {
    for ($j = $i + 1; $j < count($priorityCriteria); $j++) {
        if(($priorityCriteria[$i]['value'] < $priorityCriteria[$j]['value'])) {
            $temp = $priorityCriteria[$i];
            $priorityCriteria[$i] = $priorityCriteria[$j];
            $priorityCriteria[$j] = $temp;
        }
    }
}

for ($i = 0; $i < count($tournamentData) - 1; $i++) {
    for ($j = $i + 1; $j < count($tournamentData); $j++) {
        if(($tournamentData[$i]->points == $tournamentData[$j]->points)) {

            if($tournamentData[$i]->weight < $tournamentData[$j]->weight) {
                $temp = $tournamentData[$i];
                $tournamentData[$i] = $tournamentData[$j];
                $tournamentData[$j] = $temp;
                break;
            }

            for ($k = 0; $k < count($priorityCriteria); $k++) {
                switch ($priorityCriteria[$k]['type']) {
                    case "won":
                        if(($tournamentData[$i]->won < $tournamentData[$j]->won)) {
                            $temp = $tournamentData[$i];
                            $tournamentData[$i] = $tournamentData[$j];
                            $tournamentData[$j] = $temp;
                        }
                        break 2;
                    case "draw":
                        if(($tournamentData[$i]->draw < $tournamentData[$j]->draw)) {
                            $temp = $tournamentData[$i];
                            $tournamentData[$i] = $tournamentData[$j];
                            $tournamentData[$j] = $temp;
                        }
                        break 2;
                    case "lost":
                        if(($tournamentData[$i]->lost < $tournamentData[$j]->lost)) {
                            $temp = $tournamentData[$i];
                            $tournamentData[$i] = $tournamentData[$j];
                            $tournamentData[$j] = $temp;
                        }
                        break 2;
                    case "scored_missed":
                        if(($tournamentData[$i]->goals_for - $tournamentData[$i]->goals_against) < ($tournamentData[$j]->goals_for - $tournamentData[$j]->goals_against)) {
                            $temp = $tournamentData[$i];
                            $tournamentData[$i] = $tournamentData[$j];
                            $tournamentData[$j] = $temp;
                        }
                        break 2;
                    case "scored":
                        if($tournamentData[$i]->goals_for < $tournamentData[$j]->goals_for) {
                            $temp = $tournamentData[$i];
                            $tournamentData[$i] = $tournamentData[$j];
                            $tournamentData[$j] = $temp;
                        }
                        break 2;
                    case "missed":
                        if($tournamentData[$i]->goals_against < $tournamentData[$j]->goals_against) {
                            $temp = $tournamentData[$i];
                            $tournamentData[$i] = $tournamentData[$j];
                            $tournamentData[$j] = $temp;
                        }
                        break 2;
                    default:
                        break 2;
                }
            }
        }
    }
}

?>

<div class="search-box default-box" style="min-height: 0;">
    <div class="box-content">
        <form class="search-tournament" action="<?= Url::to(['/site/tournament']) ?>">
            <div class="select-championship selectize-box">
                <label for="select-championship">Выбрать чемпионат</label>
                <select name="championship" id="select-championship" placeholder="Выбрать тип трансферов">
                    <?php foreach ($championshipsData as $championship) {
                        $active = ($championship->active) ? 'selected class="data-default"' : '';
                    ?>
                        <option value="<?= $championship->value ?>" <?= $active ?>><?= $championship->text ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="select-season selectize-box">
                <label for="select-season">Выбрать сезон</label>
                <select name="season" id="select-season" placeholder="Выбрать сезон">
                    <?php foreach ($seasonsData as $season) {
                        $active = ($season->active) ? 'selected class="data-default"' : '';
                    ?>
                        <option value="<?= $season->value ?>" <?= $active ?>><?= $season->text ?></option>
                    <?php } ?>
                </select>
            </div>
        </form>
    </div>
</div>

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
                    $teamStatus = (in_array($count, $ECLpositionsArray)) ? 'green' : '';
                    $teamStatus = (in_array($count, $ELpositionsArray)) ? 'yellow' : $teamStatus;
                    $teamStatus = (in_array($count, $reductionPositionsArray)) ? 'red' : $teamStatus;
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