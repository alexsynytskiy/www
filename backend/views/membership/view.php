<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Membership */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Состав клубов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="membership-view">

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
                'label' => 'Команда',
                'attribute' => 'team.name',
            ],
            [
                'label' => 'Игрок',
                'attribute' => 'player.name',
            ],
            [
                'label' => 'Амплуа',
                'attribute' => 'amplua.name',
            ],
            'number',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
