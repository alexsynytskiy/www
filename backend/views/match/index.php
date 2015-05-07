<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MatchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Matches';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="match-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Match', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'is_visible',
            'championship_id',
            'command_home_id',
            'command_guest_id',
            // 'stadium_id',
            // 'season_id',
            // 'round',
            // 'date',
            // 'arbiter_main_id',
            // 'arbiter_assistant_1_id',
            // 'arbiter_assistant_2_id',
            // 'arbiter_reserve_id',
            // 'home_shots',
            // 'guest_shots',
            // 'home_shots_in',
            // 'guest_shots_in',
            // 'home_offsides',
            // 'guest_offsides',
            // 'home_corners',
            // 'guest_corners',
            // 'home_fouls',
            // 'guest_fouls',
            // 'home_yellow_cards',
            // 'guest_yellow_cards',
            // 'home_red_cards',
            // 'guest_red_cards',
            // 'home_goals',
            // 'guest_goals',
            // 'comments_count',
            // 'created_at',
            // 'updated_at',
            // 'championship_part_id',
            // 'league_id',
            // 'is_finished',
            // 'announcement:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
