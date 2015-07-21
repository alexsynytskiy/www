<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Match */

$this->title = $model->teamHome->name . ' - ' . $model->teamGuest->name;
$this->params['breadcrumbs'][] = ['label' => 'Матчи', 'url' => ['index']];
$this->params['breadcrumbs'][] =  $model->teamHome->name . ' - ' . $model->teamGuest->name;

$homePossession = isset($model->home_ball_possession) ? $model->home_ball_possession.'%' : null;
$guestPossession = isset($model->guest_ball_possession) ? $model->guest_ball_possession.'%' : null;
?>
<div class="match-view">

    <h1><?= Html::encode($model->teamHome->name . ' - ' . $model->teamGuest->name) ?></h1>

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-stats"></span> Статистика', ['stat-update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-flash"></span> События', ['events', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<span class="glyphicon glyphicon-trash"></span> Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить матч?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('<span class="glyphicon glyphicon-send"></span> Сгенерировать опрос', ['/question/autogen', 'matchID' => $model->id], ['class' => 'btn btn-success pull-right']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'is_visible',
                'value' => $model->is_visible ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_finished',
                'value' => $model->is_finished ? 'Да' : 'Нет',
            ],
            [
                'label' => 'Турнир',
                'attribute' => 'championship.name',
            ],
            [
                'label' => 'Лига',
                'attribute' => 'league.name',
            ],
            [
                'label' => 'Этап',
                'attribute' => 'championshipPart.name',
            ],
            [
                'label' => 'Хозяева',
                'attribute' => 'teamHome.name',
            ],
            [
                'label' => 'Гости',
                'attribute' => 'teamGuest.name',
            ],
            [
                'label' => 'Стадион',
                'attribute' => 'stadium.name',
            ],
            [
                'label' => 'Сезон',
                'attribute' => 'season.name',
            ],
            'date',
            [
                'label' => 'Главный арбитр',
                'attribute' => 'arbiterMain.name',
            ],
            [
                'label' => 'Лайнсмен',
                'attribute' => 'arbiterAssistant1.name',
            ],
            [
                'label' => 'Лайнсмен',
                'attribute' => 'arbiterAssistant2.name',
            ],
            [
                'label' => 'Арбитр за воротами',
                'attribute' => 'arbiterAssistant3.name',
            ],
            [
                'label' => 'Арбитр за воротами',
                'attribute' => 'arbiterAssistant4.name',
            ],
            [
                'label' => 'Резервный арбитр',
                'attribute' => 'arbiterReserve.name',
            ],
            'home_goals',
            'guest_goals',
            'home_shots',
            'guest_shots',
            'home_shots_in',
            'guest_shots_in',
            'home_offsides',
            'guest_offsides',
            'home_corners',
            'guest_corners',
            'home_fouls',
            'guest_fouls',
            'home_yellow_cards',
            'guest_yellow_cards',
            'home_red_cards',
            'guest_red_cards',            
            [
                'attribute' => 'home_ball_possession',  
                'value' => $homePossession,
            ],     
            [
                'attribute' => 'guest_ball_possession',  
                'value' => $guestPossession,
            ],   
            [
                'label' => 'Количество комментариев',
                'value' => $model->getCommentsCount(), 
            ],
            'created_at',
            'updated_at',
            [
                'attribute' => 'announcement',
                'value' => $model->announcement,
                'format' => 'html',
            ],
        ],
    ]) ?>

</div>
