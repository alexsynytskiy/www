<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TournamentSettings */

$this->title = 'Изменить настройки турнирной таблицы: ' . ' ' . $model->season->name;
$this->params['breadcrumbs'][] = ['label' => 'Настройки турнирной таблицы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->season->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="tournament-settings-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
