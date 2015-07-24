<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Career */

$this->title = $model->player->name;
$this->params['breadcrumbs'][] = ['label' => 'Карьеры игроков', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="career-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту карьеру?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'id',
            ],
            [
                'label' => 'Игрок',
                'attribute' => 'player.name',
                'value' => Html::a($model->player->name, ['/player/'.$model->player->id]),
                'format' => 'html',
            ],
            [
                'label' => 'Команда',
                'attribute' => 'team.name',
                'value' => Html::a($model->team->name, ['/team/'.$model->team->id]),
                'format' => 'html',
            ],
            [
                'attribute' => 'league.name',
                'label' => 'Лига',
                'format' => 'html',
            ],            
            [
                'label' => 'Сезон',
                'attribute' => 'season.name',
            ],
            'championship_matches',
            'championship_goals',
            'cup_matches',
            'cup_goals',
            'euro_matches',
            'euro_goals',
            'avg_mark',
            'goal_passes',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
