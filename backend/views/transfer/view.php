<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Transfer */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Трансферы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить трансфер?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'player_id',
                'value' => $model->player->name,
            ],
            [
                'label' => 'Тип трансфера',
                'attribute' => 'transferType.name',
            ],
            [
                'attribute' => 'probability',
                'value' => isset($model->probability) ? $model->probability.'%' : null,
            ],
            [
                'label' => 'С команды',
                'attribute' => 'teamFrom.name',
            ],
            [
                'label' => 'В команду',
                'attribute' => 'teamTo.name',
            ],
            [
                'label' => 'Сезон',
                'attribute' => 'season.name',
            ],
            'sum',
            [
                'attribute' => 'is_active',
                'value' => $model->is_active == 1 ? 'Да' : 'Нет',
            ],
            'clubs',
            'others',
            'contract_date',
            'created_at',
            'updated_at',
            'comments_count',
        ],
    ]) ?>

</div>
