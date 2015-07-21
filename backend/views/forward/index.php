<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ForwardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Бомбардиры';
$this->params['breadcrumbs'][] = $this->title;
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
