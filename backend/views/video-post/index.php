<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VideoPostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Видеозаписи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить видеозапись', ['create'], ['class' => 'btn btn-success']) ?>
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
                'options' => ['width' => '100'],
            ],
            [
                'attribute' => 'user.username',
                'label' => 'Автор',
                'options' => ['width' => '120'],
                'value' => function($model) {
                    return Html::a($model->user->username, ['/user/admin/view', 'id' => $model->user_id]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'title',
                'value' => function($model) {
                    return Html::a($model->title, ['/video-post/view', 'id' => $model->id]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return date('d.m.Y H:i', strtotime($model->created_at));
                },
                'format' => 'text',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'removeButton' => false,
                    'type' => DatePicker::TYPE_INPUT,
                    'language' => 'ru-RU',
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'autoclose' => true,
                    ]
                ]),
                'options' => ['width' => '140'],
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return date('d.m.Y H:i', strtotime($model->updated_at));
                },
                'format' => 'text',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'updated_at',
                    'removeButton' => false,
                    'type' => DatePicker::TYPE_INPUT,
                    'language' => 'ru-RU',
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'autoclose' => true,
                    ]
                ]),
                'options' => ['width' => '140'],
            ],
            [
                'attribute' => 'is_public',
                'value' => function($model) {
                    if($model->is_public) return 'Да';
                    return 'Нет';
                },
                'filter' => [
                    1 => 'Да',
                    0 => 'Нет',
                ],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70'],
            ],
        ],
    ]); ?>

</div>
