<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $title string Transfers title
 * @var $className string 
 * @var $transfers array Array of common\models\Transfer 
**/

if(isset($transfers)) {
    for ($i = 0; $i < count($transfers) - 1; $i++) { 
        for ($j = $i + 1; $j < count($transfers); $j++) { 
            if ($transfers[$i]->probability < $transfers[$j]->probability) {
                $temp = $transfers[$i];
                $transfers[$i] = $transfers[$j];
                $transfers[$j] = $temp;
            }
        }
    }
}

?>

<div class="default-box transfers">
    <div class="box-header">
        <div class="<?= $className ?> main-title"><?= $title ?></div>
        <div class="<?= $className ?>-icon icon"></div>
    </div>
    <div class="box-content" style="padding: 0;">

        <table class="default-table">
            <thead>
                <tr>
                    <?php if(Yii::$app->controller->action->id == 'transfers') { ?>
                        <th class="number">№</th>
                    <?php } ?>
                    <th class="photo">Фото</th>
                    <th class="player">Игрок</th>
                    <th class="possibility">ВП</th>
                    <th class="position">Амплуа</th>
                    <?php if($className == 'sell') { ?>
                        <th class="from">Куда</th>
                    <?php } else { ?>
                        <th class="where">Откуда</th>
                    <?php } ?>
                    <?php if($className == 'rent') { ?>
                        <th class="arrow"></th>
                        <th class="where">Куда</th>
                    <?php } ?>
                    <?php if($className != 'rent') { ?>
                        <th class="sum">Сумма</th>
                        <th class="others">Претенденты</th>
                    <?php } ?>
                    <?php if(Yii::$app->controller->action->id == 'transfers') { ?>
                        <th class="comments"></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                $count = 0;
                foreach ($transfers as $transfer) { 
                    $count++;
                    $player = $transfer->player;
                    
                    // if($className == 'buy' && $transfer->probability != 100) $playerUrl = false; 
                    $playerUrl = $player->getUrl();
                    if(!isset($player)) {
                        $player = new \common\models\Player;
                    }
                    $playerImage = $player->getAsset();
                    $probability = $transfer->probability;
                    if(is_numeric($transfer->probability)){
                        $probability .= '%';
                    }
                    if(isset($player->amplua)){
                        $amplua = $player->amplua->abr;
                    } else {
                        $amplua = '-';
                    }
                    if(isset($transfer->teamFrom)){
                        $teamFromName = $transfer->teamFrom->name;
                        if($transfer->teamFrom->getAsset()) {
                            $teamFromIconUrl = $transfer->teamFrom->getAsset()->getFileUrl();
                        } else {
                            $teamFromIconUrl = false;
                        }
                    } else {
                        $teamFromName = '';
                        $teamFrom = new \common\models\Team;
                        $teamFromIconUrl = false;
                    }
                    if(isset($transfer->teamTo)){
                        $teamToName = $transfer->teamTo->name;
                        if($transfer->teamTo->getAsset()) {
                            $teamToIconUrl = $transfer->teamTo->getAsset()->getFileUrl();
                        } else {
                            $teamToIconUrl = false;
                        }
                    } else {
                        $teamToName = '';
                        $teamTo = new \common\models\Team;
                        $teamToIconUrl = false;
                    }
                    $others = ($transfer->clubs == '') ? '-' : $transfer->clubs;
                    $sum = ($transfer->sum == '') ? '-' : $transfer->sum;

                    $adminLink = '';
                    if(Yii::$app->user->can('admin')) {
                      $adminLink = '<a class="admin-view-link" target="_blank" href="/admin/transfer/'.$transfer->id.'"></a>';
                    } 
                ?>
                <tr>
                    <?php if(Yii::$app->controller->action->id == 'transfers') { ?>
                        <td class="number"><?= $count ?></td>
                    <?php } ?>
                    <td class="photo">
                        <img src="<?= $playerImage->getFileUrl() ?>">
                    </td>
                    <td class="player">
                        <?php if($playerUrl) { ?>
                        <a href="<?= $playerUrl ?>">
                            <div><?= $player->firstname ?></div>
                            <div><?= $player->lastname ?></div>
                        </a>
                        <?php } else { ?>
                            <div><?= $player->firstname ?></div>
                            <div><?= $player->lastname ?></div>
                        <?php } ?>
                    </td>
                    <td class="possibility"><?= $probability ?></td>
                    <td class="position"><?= $amplua ?></td>
                    <?php if($className == 'sell') { ?>
                        <td class="where">
                            <div class="club-logo">
                                <?php if($teamToIconUrl) { ?>
                                    <img src="<?= $teamToIconUrl ?>">
                                <?php } ?>
                            </div>
                            <div class="club-name"><?= $teamToName ?></div>
                        </td>
                    <?php } else { ?>
                        <td class="from">
                            <div class="club-logo">
                                <?php if($teamFromIconUrl) { ?>
                                    <img src="<?= $teamFromIconUrl ?>">
                                <?php } ?>
                            </div>
                            <div class="club-name"><?= $teamFromName ?></div>
                        </td>
                    <?php } ?>
                    <?php if($className == 'rent') { ?>
                        <td class="arrow">
                            <div class="right-arrow"></div>
                        </td>
                        <td class="where">
                            <div class="club-logo">
                                <?php if($teamToIconUrl) { ?>
                                    <img src="<?= $teamToIconUrl ?>">
                                <?php } ?>
                            </div>
                            <div class="club-name"><?= $teamToName ?></div>
                        </td> 
                    <?php } ?>
                    <?php if($className != 'rent') { ?>
                        <td class="sum"><?= $sum ?></td>
                        <td class="others"><?= $others ?></td>
                    <?php } ?>
                    <?php if(Yii::$app->controller->action->id == 'transfers') { ?>
                        <td class="comments">
                            <?= $adminLink ?>
                            <a href="<?= $transfer->getUrl() ?>">
                                <div class="more"></div>
                            </a>
                        </td>
                    <?php } ?>
                </tr>
                <?php if($className == 'rent') { ?>
                    <tr class="rent-other-info">
                        <td class="number"></td>
                        <td class="photo"></td>
                        <td colspan="3" class="sum">Сумма: <?= $sum ?></td>
                        <td colspan="4" class="others">Претенденты: <?= $others ?></td>
                    </tr>
                <?php } ?>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>