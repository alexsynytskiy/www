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
                'value' => Html::a($model->player->name, ['/player/'.$model->player->id]),
                'format' => 'html',
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
                'label' => 'Из команды',
                'attribute' => 'teamFrom.name',
                'value' => Html::a($model->teamFrom->name, ['/team/'.$model->teamFrom->id]),
                'format' => 'html',
            ],
            [
                'label' => 'В команду',
                'attribute' => 'teamTo.name',
                'value' => Html::a($model->teamTo->name, ['/team/'.$model->teamTo->id]),
                'format' => 'html',
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
            [
                'label' => 'Количество комментариев',
                'value' => $model->getCommentsCount(), 
            ],
        ],
    ]) ?>

</div>
