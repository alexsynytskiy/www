<?php
use yii\helpers\Url;

use common\models\Amplua;

/**
 * @var $this yii\web\View
 * @var $player common\models\Player
 * @var $image common\models\Asset
**/
Yii::$app->formatter->locale = 'ru-RU';

$isGoalkeeper = false;

if($player->amplua->id == Amplua::GOALKEEPER) {
    $isGoalkeeper = true;
}

?>

<div class="default-box profile">    
    <div class="box-content">
        <div class="photo-block">
            <div class="photo">
                <img src="<?= $image->getFileUrl() ?>">
            </div>            
        </div>
        <div class="about">
            <div class="name"><?= $player->name ?></div>
            <div class="feature">
                <div class="title">Амплуа: </div>
                <div class="text"><?= $player->amplua->name ?></div>                
            </div>
            <div class="clearfix"></div>
            <?php if(isset($player->more_ampluas)) { ?>
                <div class="feature">
                    <div class="title">Дополнительные амплуа: </div>
                    <div class="text"><?= $player->more_ampluas ?></div>             
                </div>
                <div class="clearfix"></div>
            <?php } ?>
            <div class="feature">
                <div class="title">Номер: </div>
                <div class="text"><?= $player->number ?></div>             
            </div>
            <div class="clearfix"></div>
            <div class="feature">
                <div class="title">Дата рождения: </div>
                <div class="text"><?= Yii::$app->formatter->asDate($player->birthday,'dd.MM.Y') ?></div>
            </div>
            <div class="clearfix"></div>
            <div class="feature">
                <div class="title">Рост: </div>
                <div class="text"><?= $player->height ?></div>             
            </div>
            <div class="clearfix"></div>
            <div class="feature">
                <div class="title">Вес: </div>
                <div class="text"><?= $player->weight ?></div>             
            </div>
            <div class="clearfix"></div>
            <div class="text-about-person">
                <?= $player->notes ?>
            </div>      
        </div>
    </div>
</div>

<?php if(isset($player->achievements->name)) { ?>
    <div class="default-box profile-achievments">
        <div class="box-header">
            <div class="main-title">Достижения</div>
        </div> 
        <div class="box-content">
            <?= $player->achievements->name ?>
        </div>
    </div>
<?php } ?>

<div class="default-box profile-statistics">
    <div class="box-header">
        <div class="main-title">Карьера</div>
    </div>
    <div class="box-content">
        <table class="default-table">
            <thead>
                <tr>
                    <th class="season">Сезон</th>
                    <th class="league">Лига</th>
                    <th class="team">Команда</th>
                    <th class="champ-matches">ЧМ</th>
                    <th class="champ-goals">ЧГ</th>
                    <th class="cup-matches">КМ</th>
                    <th class="cup-goals">КГ</th>
                    <th class="euro-matches">ЕМ</th>
                    <th class="euro-goals">ЕГ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $careerTeamsStatistics = [];
                    foreach ($player->careers as $seasonStatistic) {

                        if(!in_array($seasonStatistic->team->id, array_keys($careerTeamsStatistics))) {
                            $careerTeamsStatistics[$seasonStatistic->team->id] = $seasonStatistic;
                        }
                        else {
                            $temp = $careerTeamsStatistics[$seasonStatistic->team->id];
                            $temp->championship_matches += $seasonStatistic->championship_matches;
                            $temp->championship_goals += $seasonStatistic->championship_goals;
                            $temp->cup_matches += $seasonStatistic->cup_matches;
                            $temp->cup_goals += $seasonStatistic->cup_goals;
                            $temp->euro_matches += $seasonStatistic->euro_matches;
                            $temp->euro_goals += $seasonStatistic->euro_goals;
                            $careerTeamsStatistics[$seasonStatistic->team->id] = $temp;
                        }
                    ?>
                    <tr>
                        <td class="season"><?= $seasonStatistic->season->name ?></td>
                        <td class="league"><?= $seasonStatistic->league->abr ?></td>
                        <td class="team"><?= $seasonStatistic->team->name ?></td>
                        <td class="champ-matches"><?= $seasonStatistic->championship_matches ?></td>
                        <td class="champ-goals">
                            <?php 
                                if($isGoalkeeper && $seasonStatistic->championship_goals != 0) {
                                    echo '-'; 
                                }
                                echo $seasonStatistic->championship_goals;
                            ?>
                        </td>
                        <td class="cup-matches"><?= $seasonStatistic->cup_matches ?></td>
                        <td class="cup-goals">
                            <?php 
                                if($isGoalkeeper && $seasonStatistic->cup_goals != 0) {
                                    echo '-'; 
                                }
                                echo $seasonStatistic->cup_goals;
                            ?>
                        </td>
                        <td class="euro-matches"><?= $seasonStatistic->euro_matches ?></td>
                        <td class="euro-goals">
                        <?php
                            if($isGoalkeeper && $seasonStatistic->euro_goals != 0) {
                                echo '-'; 
                            }
                            echo $seasonStatistic->euro_goals;
                        ?>
                        </td>
                    </tr>
                <?php
                    }
                ?>            
                <tr>
                    <td colspan="9" class="total">Всего</td>
                </tr>
                <?php 
                    foreach ($careerTeamsStatistics as $teamStatistics) {
                ?>
                    <tr>
                        <td class="season"></td>
                        <td class="league"><?= $teamStatistics->league->abr ?></td>
                        <td class="team"><?= $teamStatistics->team->name ?></td>
                        <td class="champ-matches"><?= $teamStatistics->championship_matches ?></td>
                        <td class="champ-goals">
                            <?php 
                                if($isGoalkeeper && $teamStatistics->championship_goals != 0) {
                                    echo '-'; 
                                }
                                echo $teamStatistics->championship_goals;
                            ?>
                        </td>
                        <td class="cup-matches"><?= $teamStatistics->cup_matches ?></td>
                        <td class="cup-goals">
                            <?php 
                                if($isGoalkeeper && $teamStatistics->cup_goals != 0) {
                                    echo '-'; 
                                }
                                echo $teamStatistics->cup_goals;
                            ?>
                        </td>
                        <td class="euro-matches"><?= $teamStatistics->euro_matches ?></td>
                        <td class="euro-goals">
                        <?php
                            if($isGoalkeeper && $teamStatistics->euro_goals != 0) {
                                echo '-'; 
                            }
                            echo $teamStatistics->euro_goals;
                        ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>