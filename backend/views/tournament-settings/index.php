<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TournamentSettingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройки турнирной таблицы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tournament-settings-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить настройку нового сезона', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'season_id',
            'scored_missed_weight',
            'goal_scored_weight',
            'goal_missed_weight',
            // 'win_weight',
            // 'draw_weight',
            // 'defeat_weight',
             'cl_positions',
             'el_positions',
             'reduction_positions',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
