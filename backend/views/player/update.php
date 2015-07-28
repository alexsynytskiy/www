<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Player */

$this->title = 'Изменить данные игрока: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Игроки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->firstname . ' ' . $model->lastname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="player-update">

    <?= $this->render('_form', [
        'model' => $model,
        'achievementModel' => $achievementModel,
        'achievementDataProvider' => $achievementDataProvider,
        'image' => $image,
        'careerDataProvider' => $careerDataProvider,
    ]) ?>

</div>
