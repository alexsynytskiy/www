<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Forward */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Бомбардиры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forward-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить бомбардира?',
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
                'value' => isset($model->player) ? $model->player->name : null,
            ],
            [
                'label' => 'Команда',
                'attribute' => 'team.name',
            ],
            'goals',
            'penalty',
            'matches',
        ],
    ]) ?>

</div>
