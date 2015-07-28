<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/**
 * @var $this yii\web\View
 * @var $playerID int
 * @var $dataProvider 
 */

?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="pull-right">            
            <?= Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                    'class' => 'btn btn-xs btn-primary modal-button', 
                    'title' => 'Добавить',
                    'data-url' => Url::to([
                        'career/create', 
                        'playerID' => $playerID,
                    ]),
                    'data-target' => '#career-player-add',
                ]) ?>
        </div>
        <h3 class="panel-title">
            <i class="glyphicon glyphicon-list"></i> Карьера игрока
        </h3>
    </div>

    <?php
        Pjax::begin([
            'id' => "player-gridview", 
            'timeout' => false,
            'enablePushState' => false,
            'options' => ['class' => 'pjax-container'],
        ]);
        echo GridView::widget([
            'summary' => '',
            'options' => ['class' => 'career-list'],
            'rowOptions' => function ($model, $index, $widget, $grid){
                return ['class' => 'success'];
            },
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'options' => ['width' => '30', 'class' => 'text-center'],
                ],
                [
                    'label' => 'Сезон',
                    'value' => function($model) {
                        return isset($model->season_id) ? $model->season->name : null;
                    },
                ],
                [
                    'label' => 'Лига',
                    'value' => function($model) {
                        return isset($model->league_id) ? $model->league->name : null;
                    },
                ],
                [
                    'label' => 'Команда',
                    'value' => function($model) {
                        return isset($model->command_id) ? $model->team->name : null;
                    },
                ],
                [
                    'label' => 'ЧМ',
                    'value' => function($model) {
                        return isset($model->championship_matches) ? $model->championship_matches : null;
                    },
                ],
                [
                    'label' => 'ЧГ',
                    'value' => function($model) {
                        return isset($model->championship_goals) ? $model->championship_goals : null;
                    },
                ],
                [
                    'label' => 'КМ',
                    'value' => function($model) {
                        return isset($model->cup_matches) ? $model->cup_matches : null;
                    },
                ],
                [
                    'label' => 'КГ',
                    'value' => function($model) {
                        return isset($model->cup_goals) ? $model->cup_goals : null;
                    },
                ],
                [
                    'label' => 'ЕМ',
                    'value' => function($model) {
                        return isset($model->euro_matches) ? $model->euro_matches : null;
                    },
                ],
                [
                    'label' => 'ЕГ',
                    'value' => function($model) {
                        return isset($model->euro_goals) ? $model->euro_goals : null;
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'options' => ['width' => 70],
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            $customUrl = Url::to(['career/delete', 'id' => $model['id']]);
                            return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                                'title' => 'Удалить', 
                                'class' => 'btn btn-xs btn-default delete-button',
                                'data-url' => $customUrl,
                            ]);
                        },
                        'update' => function ($url, $model) {
                            $customUrl = Url::to(['career/update', 'id' => $model['id']]);
                            return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                                'title' => 'Изменить', 
                                'class' => 'btn btn-xs btn-default modal-button',
                                'data-url' => $customUrl,
                                'data-target' => '#career-player-edit',
                            ]);
                        },
                    ],
                ],
            ],
        ]);   
        Pjax::end();
    ?>

    <!-- Modal add player form -->
    <?php 
        Modal::begin([
            'header' => '<h4 style="margin:0;">Изменить карьеру</h4>', 
            'id' => 'career-player-edit',
            'size' => 'modal-lg',
        ]);
        Modal::end();
    ?>
    <?php 
        Modal::begin([
            'header' => '<h4 style="margin:0;">Добавить карьеру</h4>', 
            'id' => 'career-player-add',
            'size' => 'modal-lg',
        ]);
        Modal::end();
    ?>
    <?php // Pjax::end(); ?>
</div>