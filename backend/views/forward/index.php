<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\Season;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ForwardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Бомбардиры';
$this->params['breadcrumbs'][] = $this->title;

$seasons = Season::find()
    ->innerJoinWith('forwards')
    ->all();
$seasonFilter = ArrayHelper::map($seasons, 'id', 'name');
?>
<div class="forward-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить бомбардира', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'season_id',
                'value' => function($model) {
                    return isset($model->season) ? $model->season->name : null;
                },
                'filter' => $seasonFilter,
                'options' => ['width' => '120'],
            ],
            [
                'label' => 'Игрок',
                'attribute' => 'player.lastname',
                'value' => function($model) {
                    return isset($model->player) ? $model->player->name : null;
                },
            ],
            [
                'label' => 'Команда',
                'attribute' => 'team.name',
            ],
            'goals',
            'penalty',
            'matches',

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70'],
            ],
        ],
    ]); ?>

</div>
