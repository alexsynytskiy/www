<?php

use common\models\Season;

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ContractSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Игроки команд';
$this->params['breadcrumbs'][] = $this->title;

$contractTable = $searchModel::tableName();
$seasonTable = Season::tableName();
$seasons = Season::find()
    ->innerJoin($contractTable, "{$contractTable}.season_id = {$seasonTable}.id")
    ->all();
$seasonFilter = ArrayHelper::map($seasons, 'id', 'name');
?>
<div class="contract-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить игрока в команду', ['create'], ['class' => 'btn btn-success']) ?>
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
            // ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'options' => ['width' => '70'],
            ],
            [
                'label' => 'Игрок',
                'attribute' => 'player.lastname',
                'value' => function($model) {
                    return Html::a($model->player->name, ['/player/'.$model->player->id]);
                },
                'format' => 'html',
            ],
            [
                'label' => 'Команда',
                'attribute' => 'team.name',
                'value' => function($model) {
                    return Html::a($model->team->name, ['/team/'.$model->team->id]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'season_id',
                'value' => function($model) {
                    return $model->season->name;
                },
                'filter' => $seasonFilter,
                'options' => ['width' => '120'],
            ],
            [
                'label' => 'Амплуа',
                'attribute' => 'amplua.name',
                'value' => function($model) {
                    return isset($model->amplua->name) ? $model->amplua->name : null;
                },
            ],
            // 'number',
            // 'command_from_id',
            // 'year_from',
            // 'year_till',
            // 'matches',
            // 'goals',
            // 'is_active',
            // 'debut',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
