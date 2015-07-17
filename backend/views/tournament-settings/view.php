<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TournamentSettings */

$this->title = 'Сезон '.$model->season->name;
$this->params['breadcrumbs'][] = ['label' => 'Настройки сезона', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tournament-settings-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить настройки?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Сезон',
                'attribute' => 'season.name',
            ],
            'scored_missed_weight',
            'goal_scored_weight',
            'goal_missed_weight',
            'win_weight',
            'draw_weight',
            'defeat_weight',
            'cl_positions',
            'el_positions',
            'reduction_positions',
        ],
    ]) ?>

</div>
