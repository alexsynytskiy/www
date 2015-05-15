<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Match */

$this->title = $model->commandHome->name . ' - ' . $model->commandGuest->name;
$this->params['breadcrumbs'][] = ['label' => 'Матчи', 'url' => ['index']];
$this->params['breadcrumbs'][] =  $model->commandHome->name . ' - ' . $model->commandGuest->name;
?>
<div class="match-view">

    <h1><?= Html::encode($model->commandHome->name . ' - ' . $model->commandGuest->name) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить матч?',
                'method' => 'post',
            ],
        ]) ?>
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
                'attribute' => 'commandHome.name',
            ],
            [
                'label' => 'Гости',
                'attribute' => 'commandGuest.name',
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
            'comments_count',
            'created_at',
            'updated_at',            
            'announcement:ntext',
        ],
    ]) ?>

</div>
