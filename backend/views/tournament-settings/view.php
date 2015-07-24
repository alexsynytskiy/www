<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TournamentSettings */

$this->title = $model->season->name;
$this->params['breadcrumbs'][] = ['label' => 'Настройки турнирной таблицы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tournament-settings-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить настройки этого сезона?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
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
