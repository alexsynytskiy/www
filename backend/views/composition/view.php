<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Composition */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Составы команд матчей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="composition-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Матч',
                'value' => $model->match->name,
            ],
            [
                'label' => 'Команда',
                'value' => $model->team->name,
            ],
            [
                'label' => 'Игрок',
                'value' => $model->name,
            ],
            [
                'attribute' => 'is_substitution',
                'value' => $model->is_substitution ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_basis',
                'value' => $model->is_basis ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_captain',
                'value' => $model->is_captain ? 'Да' : 'Нет',
            ],
            'number',
        ],
    ]) ?>

</div>
