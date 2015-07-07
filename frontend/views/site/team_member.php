<?php
use yii\helpers\Url;

use common\models\Amplua;

/**
 * @var $this yii\web\View
 * @var $player common\models\Player
 * @var $image common\models\Asset
**/

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
            <?php if($player->more_ampluas != "") { ?>
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
                <div class="text"><?= $player->birthday ?></div>             
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

<?php if($player->achievements->name != "") { ?>
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
            </tdead>
            <tbody>
                <?php 
                    foreach ($player->careers as $seasonStatistic) { ?>
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
                <tr>
                    <td class="season"></td>
                    <td class="league">м</td>
                    <td class="team">"Динамо-м" К</td>
                    <td class="champ-matches">8</td>
                    <td class="champ-goals">0</td>
                    <td class="cup-matches">0</td>
                    <td class="cup-goals">0</td>
                    <td class="euro-matches">0</td>
                    <td class="euro-goals">0</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>