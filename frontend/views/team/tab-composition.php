<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $teamModel common\models\Team 
 * @var $availableTeams array of common\models\Team 
 * @var $activeTeam int
 * @var $availableSeasons array of common\models\Season 
 * @var $activeSeason int
 * @var $composition array of common\models\Contract 
**/
?>

<div class="search-box default-box composition-search">
    <form class="search-composition" action="">
        <div class="club-select">
            <label>Выбрать Команду</label>
            <?php 
                foreach ($availableTeams as $team) {
                    if($team->id == $activeTeam) {
                        $active = 'active';
                    }
                    else {
                        $active = '';
                    }
            ?>
                <a href="/team/composition/<?= $team->id ?>">
                    <div class="button <?= $active ?>"><?= $team->name ?><div class="select"></div></div>
                </a>
            <?php
                }
            ?>
        </div>
        <div class="select-season selectize-box">
            <label for="select-season">Выбрать сезон</label>
            <select name="season" id="select-season" placeholder="Выбрать сезон">
                <option value="">Выбрать сезон</option>
                <?php 
                    foreach ($availableSeasons as $season) {
                        if($season->id == $activeSeason) {
                            $active = 'selected class="data-default"';
                        }
                        else {
                            $active = '';
                        }
                ?>
                    <option value="<?= $season->id ?>" <?= $active ?>>Cезон <?= $season->name ?></option>
                <?php
                    }
                ?>
            </select>
        </div>
    </form>
</div>

<div class="default-box composition-box">
    <div class="box-content" style="padding: 0;">
        <table class="default-table composition-table">
            <thead>
                <tr>
                    <th class="number">№</th>
                    <th class="photo">Фото</th>
                    <th class="player">Игрок</th>
                    <th class="country"></th>
                    <th class="birthday">ДР</th>
                    <th class="height">Рост (см)</th>
                    <th class="weight">Вес (кг)</th>
                    <th class="from">Пришел из клуба</th>
                    <th class="year">В году</th>
                    <th class="matches">Матчи</th>
                    <th class="goals">Голы</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $count = 0;
                    $amplua = 0;
                    foreach ($composition as $contract) { 
                        $count++;
                        $player = $contract->player;
                        if(!isset($player)) {
                            $player = new \common\models\Player;
                        }
                        $playerImage = $player->getAsset();
                        $country = $player->country;
                        $countryIconUrl = isset($country) ? $country->getAsset()->getFileUrl() : false;
                        $teamFromName = isset($contract->teamFrom) ? $contract->teamFrom->name : '-';
                        if($amplua != $contract->amplua->id) {
                            $amplua = $contract->amplua->id;
                            ?>
                            <tr><td colspan="11" class="caption"><?= $contract->amplua->name ?></td></tr>
                            <?php
                        }
                ?>
                <tr>
                    <td class="number"><?= $count ?></td>
                    <td class="photo">
                        <img src="<?= $playerImage->getFileUrl() ?>" alt="player image">
                    </td>
                    <td class="player">
                        <a href="<?= $player->getUrl() ?>">
                            <?= $player->name ?>
                        </a> 
                    </td>
                    <td class="country">
                        <?php if($countryIconUrl) { ?>
                            <img src="<?= $countryIconUrl ?>" alt='country-icon'>
                        <?php } ?>
                    </td>
                    <td class="birthday">
                        <?= date('d.m.Y', strtotime($player->birthday)) ?>
                    </td>
                    <td class="height"><?= $player->height ?></td>
                    <td class="weight"><?= $player->weight ?></td>
                    <td class="from"><?= $teamFromName ?></td>
                    <td class="year"><?= $contract->year_from ?></td>
                    <td class="matches"><?= $contract->matches ?></td>
                    <td class="goals"><?= $contract->goals ?></td>
                </tr>
                <?php } ?>  
            </tbody>
        </table>
    </div>
</div>
                