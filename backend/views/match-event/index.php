<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MatchEventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'События матчей';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="match-event-index">

    <p>
        <?php
            // if(count(Yii::$app->getRequest()->getQueryParams()) > 0) {
            //     echo Html::a('Сброс', ['/'.Yii::$app->controller->id.'/'.Yii::$app->controller->action->id], ['class' => 'btn btn-primary']);
            // } 
        ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            // [
            //     'attribute' => 'match_id',
            //     'label' => 'Матч',
            //     'options' => ['width' => '250'],
            //     'value' => function($model) {
            //         return $model->match->name;
            //     },
            // ],
            [
                'label' => 'События матча',
                'attribute' => 'match_event_type_id',
                'value' => function($model) {
                    return isset($model->matchEventType) ? $model->matchEventType->name : 'Комментарий';
                },
                'filter' => $eventFilter,
                'options' => ['width' => '120'],
            ],
            [
                'attribute' => 'composition_id',
                'label' => 'Игрок',
                'options' => ['width' => '250'],
                'value' => function($model) {
                    return isset($model->composition) ? $model->composition->name : null;
                },
            ],
            [
                'attribute' => 'minute',
                'label' => 'Минута',
                'options' => ['width' => '70'],
                'format' => 'html',
                'value' => function($model) {
                    return $model->getTime();
                },
            ],
            [
                'attribute' => 'notes',
                'label' => 'Комментарий',
                'options' => ['width' => '370'],
                'format' => 'html',
            ],
            // 'is_hidden',
            // 'position',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        $customUrl = Url::to(['match-event/delete', 'id' => $model['id']]);
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl,[
                            'title' => 'Удалить',
                            'data-method' => 'post',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        $customUrl = Url::to(['match-event/update', 'id' => $model['id']]);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $customUrl,[
                            'title' => 'Изменить',
                        ]);
                    },
                ],
                'options' => ['width' => 70],
            ],
        ],
    ]); ?>

</div>
