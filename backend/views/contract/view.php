<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Contract */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Игроки команд', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить игрока с клуба?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
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
                'label' => 'Сезон',
                'attribute' => 'season.name',
            ],
            [
                'label' => 'Амплуа',
                'attribute' => 'amplua.name',
            ],
            'number',
            [
                'label' => 'Из команды',
                'attribute' => 'teamFrom.name',
                'value' => isset($model->teamFrom) ? Html::a($model->teamFrom->name, ['/team/'.$model->teamFrom->id]) : null,
                'format' => 'html',
            ],
            'year_from',
            'year_till',
            'matches',
            'goals',
            'is_active',
            'debut',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
