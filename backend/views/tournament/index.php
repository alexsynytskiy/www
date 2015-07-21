<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TournamentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $availableSeasons array Array of common\models\Season */
/* @var $availableLeagues array Array of common\models\League */

$this->title = 'Турнир';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="tournament-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить команду в турнир', ['create'], ['class' => 'btn btn-success']) ?>
        <?php
            if(count(Yii::$app->getRequest()->getQueryParams()) > 0) {
                echo Html::a('Сброс', ['/'.Yii::$app->controller->id], ['class' => 'btn btn-primary']);
            } 
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'team.name',
            // 'championship.name',
            // [
            //     'attribute' => 'league_id',
            //     'value' => function($model){
            //         return $model->league->name;
            //     },
            //     'filter' => $availableLeagues,
            //     'options' => ['width' => '120'],
            // ],
            [
                'attribute' => 'season_id',
                'value' => function($model){
                    return $model->season->name;
                },
                'filter' => $availableSeasons,
                'options' => ['width' => '90'],
            ],
            'played',
            'won',
            'draw',
            'lost',
            'goals_for',
            'goals_against',
            'points',
            // 'created_at',
            // 'updated_at',
            // 'fair_play',

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70'],
            ],
        ],
    ]); ?>

</div>
