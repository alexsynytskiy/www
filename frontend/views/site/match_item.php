<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $model common\models\Match
**/

if(!isset($model->championshipPart)) {
var_dump($model->id);
die;
}
?>

<tr>
    <td class="status <?= $model->checkMatchWinner() ?>"></td>
    <th class="date"><?= date("j.n.Y", strtotime($model->date)) ?> </th>
    <th class="competition"><?= $model->championship->name.', '.$model->championshipPart->name ?></th>
    <th class="home"><?= $model->commandHome->name ?></th>
    <th class="logo">
        <img src="/images/1.jpg">
    </th>
    <th class="score"><?= $model->home_goals.':'.$model->guest_goals ?></th>
    <th class="logo">
        <img src="/images/17.jpg">
    </th>
    <th class="visitors"><?= $model->commandGuest->name ?></th>
    <th class="more">
        <a href="/?q=translation">
            <div class="link">
                <div class="arrow"></div>
            </div>
        </a>                     
    </th>
</tr>