<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TeamCoach */

$this->title = $model->coach->name;
$this->params['breadcrumbs'][] = ['label' => 'Тренерский состав', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-coach-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить тренера из команды?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'team.name',
            'season.name',
            'coach.name',
            [
                'attribute' => 'is_main',
                'value' => $model->is_main ? 'Да' : 'Нет',
            ],
        ],
    ]) ?>

</div>
