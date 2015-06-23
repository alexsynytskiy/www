<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\QuestionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Опросы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать опрос', ['create'], ['class' => 'btn btn-success']) ?>
        <?php
            if(count(Yii::$app->getRequest()->getQueryParams()) > 0) {
                echo Html::a('Сброс', ['/'.Yii::$app->controller->id], ['class' => 'btn btn-primary']);
            } 
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'options' => ['width' => '70'],
            ],
            'title',
            [
                'attribute' => 'voutes',
                'options' => ['width' => '90'],
            ],
            [
                'attribute' => 'is_active',
                'value' => function($model) {
                    if($model->is_active) return 'Да';
                    return 'Нет';
                },
                'filter' => [
                    1 => 'Да',
                    0 => 'Нет',
                ],
                'options' => ['width' => '90'],
            ],
            [
                'attribute' => 'is_multipart',
                'value' => function($model) {
                    if($model->is_multipart) return 'Да';
                    return 'Нет';
                },
                'filter' => [
                    1 => 'Да',
                    0 => 'Нет',
                ],
                'options' => ['width' => '90'],
            ],
            [
                'attribute' => 'is_float',
                'value' => function($model) {
                    if($model->is_float) return 'Да';
                    return 'Нет';
                },
                'filter' => [
                    1 => 'Да',
                    0 => 'Нет',
                ],
                'options' => ['width' => '90'],
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return date('d.m.Y', strtotime($model->created_at));
                },
                'filter' => false,
                'options' => ['width' => '90'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70'],
            ],
        ],
    ]); ?>

</div>
