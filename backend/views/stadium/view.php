<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Stadium */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Стадионы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stadium-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить стадион?',
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
                'attribute' => 'spectators',
                'label' => 'Вместимость',
            ],
            [
                'attribute' => 'country.name',
                'label' => 'Страна',
            ],
        ],
    ]) ?>

</div>
