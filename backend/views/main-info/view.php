<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\MainInfo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Информация о команде', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-info-view">

    <h1><?= Html::encode($model->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить информацию?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'title',
            'content:html',
        ],
    ]) ?>

</div>
