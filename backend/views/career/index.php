<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\Season;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CareerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Карьеры игроков';
$this->params['breadcrumbs'][] = $this->title;

$careerTable = $searchModel::tableName();
$seasonTable = Season::tableName();
$seasons = Season::find()
    ->innerJoin($careerTable, "{$careerTable}.season_id = {$seasonTable}.id")
    ->all();
$seasonFilter = ArrayHelper::map($seasons, 'id', 'name');
?>
<div class="career-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить карьеру', ['create'], ['class' => 'btn btn-success']) ?>
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
                'attribute' => 'id',
            ],
            [
                'attribute' => 'player.lastname',
                'label' => 'Игрок',
                'value' => function($model) {
                    return Html::a($model->player->name, ['/player/'.$model->player->id]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'team.name',
                'label' => 'Команда',
                'value' => function($model) {
                    return Html::a($model->team->name, ['/team/'.$model->team->id]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'league.name',
                'label' => 'Лига',
                'format' => 'html',
            ],            
            [
                'attribute' => 'season_id',
                'value' => function($model) {
                    return $model->season->name;
                },
                'filter' => $seasonFilter,
            ],
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
