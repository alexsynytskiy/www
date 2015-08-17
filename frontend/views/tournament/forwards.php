<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $tournamentData array Array of common\models\Forward 
**/
?>

<div id="best-forwards" class="best-forwards default-box">
    <div class="box-header">
        <div class="box-title">Лучшие бомбардиры</div>
    </div>
    <div class="box-content">
        <table class="default-table">
            <thead>
                <tr>
                    <th class="num"></th>
                    <th class="country-icon"></th>
                    <th class="player">Игрок</th>
                    <th class="team">Команда</th>
                    <th class="goals">Голы</th>
                    <th class="penalty">Пенальти</th>
                    <th class="games">Игры</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $count = 0;
                foreach($forwards as $forward) {
                    $count++;
                    $highlight = $forward->team_id == \common\models\Team::TEAM_DK_FIRST_FULL_NAME ? 'bold' : '';
                    $playerName = $forward->player->name;
                    $teamName = $forward->team->name;
                    $country = $forward->player->country;
                    $countryIconUrl = isset($country) ? $country->getAsset()->getFileUrl() : false;
                    ?>
                <tr>
                    <td class="num"><?= $count ?></td>
                    <td class="country-icon">
                    <?php if($countryIconUrl) { ?>
                        <img src="<?= $countryIconUrl ?>" alt='country-icon'>
                    <?php } ?>
                    </td>
                    <td class="player"><a href="<?= $forward->player->getUrl() ?>"><?= $playerName ?></a></td>
                    <td class="team <?= $highlight ?>"><?= $teamName ?></td>
                    <td class="goals"><?= $forward->goals ?></td>
                    <td class="penalty"><?= $forward->penalty ?></td>
                    <td class="games"><?= $forward->matches ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>