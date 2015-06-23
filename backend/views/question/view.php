<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить опрос?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'voutes',
            [
                'attribute' => 'is_active',
                'value' => $model->is_active ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_multipart',
                'value' => $model->is_multipart ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_float',
                'value' => $model->is_float ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'created_at',
                'value' => date('d.m.Y H:i:s', strtotime($model->created_at)),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('d.m.Y H:i:s', strtotime($model->updated_at)),
            ],
        ],
    ]) ?>

    <?php 
    if(is_null($model->parent_id)) { 
        echo $this->render('answers_view', [
            'question' => $model,
            'answerForm' => $answerForm,
            'dataProvider' => $answersDataProvider,
        ]);  
    } 
    ?>


</div>
