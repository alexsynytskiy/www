<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MatchSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Матчи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="match-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить матч', ['create'], ['class' => 'btn btn-success']) ?>
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
                'options' => ['width' => '80'],
            ],
            [
                'attribute' => 'championship.name',
                'label' => 'Турнир',
                'options' => ['width' => '170'],
                'format' => 'html',
            ],
            [
                'attribute' => 'commandHome.name',
                'label' => 'Хозяева',
                'options' => ['width' => '150'],
                'format' => 'html',
            ],
            [
                'attribute' => 'commandGuest.name',
                'label' => 'Гости',
                'options' => ['width' => '150'],
                'format' => 'html',
            ],
            [
                'attribute' => 'stadium.name',
                'label' => 'Стадион',
                'options' => ['width' => '180'],
                'format' => 'html',
            ],
            [
                'attribute' => 'date',
                'value' => function($model){
                    return date('d.m.Y h:i', strtotime($model->date));
                },
                'format' => 'text',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date',
                    'removeButton' => false,
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'autoclose' => true,
                    ]
                ]),
                'options' => ['width' => '160'],
            ],
            [
                'attribute' => 'home_goals',
                'options' => ['width' => '50'],
            ],
            [
                'attribute' => 'guest_goals',
                'options' => ['width' => '50'],
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
