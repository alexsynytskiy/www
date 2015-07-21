<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MatchEvent */

$this->title = $model->match->name;
$this->params['breadcrumbs'][] = ['label' => 'События матча', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="match-event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить это событие?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
        $minute = 0;
        if($model->additional_minute != NULL) {
            $minute = $model->minute."+".$model->additional_minute;
        }
        else {
            $minute = $model->minute;
        }        
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
             [
                'attribute' => 'match_id',
                'label' => 'Матч',
                'value' => $model->match->name,
            ],
            [
                'label' => 'Событие матча',
                'attribute' => 'match_event_type_id',
                'value' => $model->matchEventType->name,
            ],
            [
                'attribute' => 'composition_id',
                'label' => 'Игрок',
                'value' => $model->composition->name,
            ],
            [
                'attribute' => 'minute',
                'label' => 'Минута',
                'format' => 'html',
                'value' => $minute,
            ],
            [
                'attribute' => 'notes',
                'label' => 'Комментарий',
                'format' => 'html',
            ],
            [
                'attribute' => 'is_hidden',
                'value' => $model->is_hidden ? 'Да' : 'Нет',
            ],
        ],
    ]) ?>

</div>
