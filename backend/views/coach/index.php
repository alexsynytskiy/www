<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CoachSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Тренеры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить тренера', ['create'], ['class' => 'btn btn-success']) ?>
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
            'name',
            [
                'attribute' => 'birthday',
                'value' => function($model){
                    return date('d.m.Y', strtotime($model->birthday));
                },
                'format' => 'text',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'birthday',
                    'removeButton' => false,
                    'type' => DatePicker::TYPE_INPUT,
                    'language' => 'ru-RU',
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'autoclose' => true,
                    ]
                ]),
                'options' => ['width' => '100'],
            ],
            'slug',
            'position',
            // 'notes:ntext',
            // 'player_carrer:ntext',
            // 'coach_carrer:ntext',
            // 'country_id',
            // 'created_at',
            // 'updated_at',
            // 'image',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
