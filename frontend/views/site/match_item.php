<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $model common\models\Match
**/

?>

<tr>
    <td class="status <?= $model->checkMatchWinner() ?>"></td>
    <th class="date"><?= date("d.m.Y", strtotime($model->date)) ?></th>
    <th class="competition"><?= $model->getTournamentName() ?></th>
    <th class="home"><?= $model->teamHome->name ?></th>
    <?php
        $image = $model->getAssetHome();        
    ?>
    <th class="logo">
        <?php if (isset($image->id)) { ?>
            <img src="<?= $image->getFileUrl() ?>" style="height: 30px; width: 30px;">
        <?php } ?>
    </th>    
    <th class="score"><?= $model->home_goals.':'.$model->guest_goals ?></th>
    <?php
        $image = $model->getAssetGuest();        
    ?>
    <th class="logo">
        <?php if (isset($image->id)) { ?>
            <img src="<?= $image->getFileUrl() ?>" style="height: 30px; width: 30px;">
        <?php } ?>
    </th>    
    <th class="visitors"><?= $model->teamGuest->name ?></th>
    <th class="more">
        <a href="<?= Url::to('/match/'.$model->id) ?>">
            <div class="link">
                <div class="arrow"></div>
            </div>
        </a>                     
    </th>
</tr>