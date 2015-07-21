<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CareerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Careers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="career-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Career', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'player_id',
            'league_id',
            'season_id',
            'command_id',
            // 'championship_matches',
            // 'championship_goals',
            // 'cup_matches',
            // 'cup_goals',
            // 'euro_matches',
            // 'euro_goals',
            // 'avg_mark',
            // 'goal_passes',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
