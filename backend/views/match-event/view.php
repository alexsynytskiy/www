<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MatchEvent */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Match Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="match-event-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'match_id',
            'match_event_type_id',
            'composition_id',
            'minute',
            'notes:ntext',
            'created_at',
            'updated_at',
            'substitution_id',
            'additional_minute',
            'is_hidden',
            'position',
        ],
    ]) ?>

</div>
