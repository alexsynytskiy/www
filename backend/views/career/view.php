<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Career */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Careers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="career-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'player_id',
            'league_id',
            'season_id',
            'command_id',
            'championship_matches',
            'championship_goals',
            'cup_matches',
            'cup_goals',
            'euro_matches',
            'euro_goals',
            'avg_mark',
            'goal_passes',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
