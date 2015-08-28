<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $teams array Array of common\models\Tournament
**/
?>

<div class="scoreboard default-box">
    <div class="box-header">
        <div class="box-title">Турнирная таблица</div>
        <a href="<?= Url::to(['/site/tournament']) ?>">
            <div class="box-link">Детальнее:<div class="icon-arrow"></div></div>
        </a>
    </div>
    <div class="box-content">
        <table>
            <thead>
                <th class="num">М</th>
                <th class="team">Команда</th>
                <th class="score">О</th>
            </thead>
            <tbody>
            <?php
            $count = 0; 

            foreach ($teams as $teamData) {
                $team = $teamData->team;
                $count++;
                if($team->id == $team::TEAM_DK_FIRST_FULL_NAME) {
                    $dynamoPos = $count;
                    break;
                }
            }

            $count = 0;
            $topIndex = 4;
            $skipHtml = '<td class="num"></td><td class="team"></td><td class="score"></td>';
            if($dynamoPos == $topIndex + 1 || $dynamoPos == count($teams) - $topIndex){
                $topIndex++;
            }
            foreach ($teams as $teamData) {
                $bottomSkip = $topSkip = $middleSkip = false;
                $points = $teamData->points;
                $team = $teamData->team;
                $count++;
                $rowClass = '';
                if($count == $dynamoPos) {
                    $rowClass = 'highlighted';
                }
                if($dynamoPos <= $topIndex || $dynamoPos > count($teams) - $topIndex){
                    if($count > $topIndex && $count <= count($teams) - $topIndex) continue;
                    if($count == $topIndex) $middleSkip = true;
                } else {
                    if($count == $dynamoPos) {
                        $topSkip = true;
                        $bottomSkip = true;
                    } elseif($count > $topIndex && $count <= count($teams) - $topIndex) continue;
                }
                ?>

                <?php if($topSkip) { ?>
                    <tr class="top-skip"><?= $skipHtml ?></tr>
                <?php } ?>
                <tr class="row-<?= $count ?> <?= $rowClass ?>">
                    <td class="num"><?= $count ?></td>
                    <td class="team"><?= $team->name ?></td>
                    <td class="score"><?= $points ?></td>
                </tr>
                <?php if($middleSkip) { ?>
                    <tr class="top-skip"><?= $skipHtml ?></tr>
                <?php } ?>
                <?php if($bottomSkip || $middleSkip) { ?>
                    <tr class="bottom-skip"><?= $skipHtml ?></tr>
                <?php } ?>
            <?php } ?>
                <tr class="last-row"></tr>
            </tbody>
        </table>
    </div>
</div>