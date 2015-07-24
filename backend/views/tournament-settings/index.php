<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\Season;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TournamentSettingsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Настройки турнирной таблицы';
$this->params['breadcrumbs'][] = $this->title;

$settingsTable = $searchModel::tableName();
$seasonTable = Season::tableName();
$seasons = Season::find()
    ->innerJoin($settingsTable, "{$settingsTable}.season_id = {$seasonTable}.id")
    ->all();
$seasonFilter = ArrayHelper::map($seasons, 'id', 'name');
?>
<div class="tournament-settings-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить настройку нового сезона', ['create'], ['class' => 'btn btn-success']) ?>
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
            [
                'attribute' => 'season_id',
                'value' => function($model) {
                    return $model->season->name;
                },
                'filter' => $seasonFilter,
            ],
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
