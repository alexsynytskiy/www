<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TeamCoachSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Тренерский состав';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-coach-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить тренера в команду', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'team.name',
                'label' => 'Команда',
            ],
            [
                'attribute' => 'season.name',
                'label' => 'Сезон',
            ],
            [
                'attribute' => 'coach.name',
                'label' => 'Тренер',
            ],
            [
                'attribute' => 'is_main',
                'filter' => [
                    1 => 'Да',
                    0 => 'Нет',
                ],
                'value' => function($model) {
                    if($model->is_main) return 'Да';
                    return 'Нет';
                },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
