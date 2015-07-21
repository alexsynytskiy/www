<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Tournament */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Турнир', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tournament-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'team.name',
            'championship.name',
            'league.name',
            'season.name',
            'played',
            'won',
            'draw',
            'lost',
            'goals_for',
            'goals_against',
            'points',
            'penalty_points',
            'weight',
            'created_at',
            'updated_at',
            'fair_play',
        ],
    ]) ?>

</div>
