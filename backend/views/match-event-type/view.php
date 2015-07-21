<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MatchEventType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'События матча', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$icon = $model->getAsset();
$iconIMG = '<img src="'.$icon->getFileUrl().'" style="height:25px; width: 25px;">';
?>
<div class="match-event-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить это событие матча',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'name',
                'label' => 'Название',
            ],
            [
                'label' => 'Иконка',
                'value' => $iconIMG,
                'format' => 'html',
            ],
        ],
    ]) ?>

</div>
