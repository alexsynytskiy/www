<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/**
 * @var $this yii\web\View
 * @var $teamId int
 * @var $team string 
 * @var $dataProvider 
 * @var $compositionForm 
 * @var $composition
 */

?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="pull-right">
            <?= Html::button('<i class="glyphicon glyphicon-pencil"></i>', [
                    'class' => 'btn btn-xs btn-primary', 
                    'title' => 'Изменить',
                    'data-toggle' => 'modal',
                    'data-target' => '#team'.$teamId.'-dual-list',
                ]) ?>

            <?= Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                    'class' => 'btn btn-xs btn-primary modal-button', 
                    'title' => 'Добавить',
                    'data-url' => Url::to([
                        'composition/create', 
                        'matchId' => $compositionForm->match_id,
                        'teamId' => $teamId,
                    ]),
                    'data-target' => '#team'.$teamId.'-player-add',
                ]) ?>
        </div>
        <h3 class="panel-title">
            <i class="glyphicon glyphicon-list"></i> Состав команды
        </h3>
    </div>
    <?php
        Pjax::begin([
            'id' => "team$teamId-gridview", 
            'timeout' => false,
            'enablePushState' => false,
            'options' => ['class' => 'pjax-container'],
        ]);
        echo GridView::widget([
            'summary' => '',
            'options' => ['class' => 'team-list'],
            'rowOptions' => function ($model, $index, $widget, $grid){
                return !$model->is_basis ? ['class' => 'danger'] : ['class' => 'success'];
            },
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'label' => 'Игрок',
                    'value' => function($model) {
                        $num = isset($model->number) && $model->number ? ' #'.$model->number : '';
                        return isset($model->contract) ? $num." ".$model->contract->player->lastname." ".$model->contract->player->firstname : null;
                    },
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'options' => ['width' => 70],
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            $customUrl = Url::to(['composition/delete', 'id' => $model['id']]);
                            return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                                'title' => 'Удалить', 
                                'class' => 'btn btn-xs btn-default delete-button',
                                'data-url' => $customUrl,
                            ]);
                        },
                        'update' => function ($url, $model) {
                            $customUrl = Url::to(['composition/update', 'id' => $model['id']]);
                            return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                                'title' => 'Изменить', 
                                'class' => 'btn btn-xs btn-default modal-button',
                                'data-url' => $customUrl,
                                'data-target' => '#team'.$model->command_id.'-player-edit',
                            ]);
                        },
                    ],
                ],
            ],
        ]);   
        Pjax::end();
    ?>
    <!-- Modal composition form -->
    <?php 
        Modal::begin([
            'header' => '<h4 style="margin:0;">Изменить состав</h4>', 
            'id' => 'team'.$teamId.'-dual-list',
            'size' => 'modal-lg',
            'footer' => Html::button('Закрыть', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']).
                Html::button('Сохранить изменения', [
                    'class' => 'btn btn-primary dual-list-submit',
                    'data-url' => Url::to(['/composition/update-list']),
                    'data-teamId' => $teamId,
                    'data-matchId' => $compositionForm->match_id,
                ]),
        ]);
    ?>
    <div class="dual-list-form">
        <?= maksyutin\duallistbox\Widget::widget([
            'model' => $compositionForm,
            'attribute' => $team.'Players',
            'title' => 'Игроки',
            'data' => $composition,
            'data_id'=> 'id',
            'data_value' => "name",
            'lngOptions' => [
                'search_placeholder' => 'Поиск',
                'available' => 'Возможные',
                'selected' => 'Выбранные',
                'showing' => '- всего',
            ],
        ]) ?>
    </div>
    <?php
        Modal::end();
    ?>

    <!-- Modal add player form -->
    <?php 
        Modal::begin([
            'header' => '<h4 style="margin:0;">Добавить игрока</h4>', 
            'id' => 'team'.$teamId.'-player-add',
            'size' => 'modal-lg',
        ]);
        Modal::end();
    ?>
    <?php 
        Modal::begin([
            'header' => '<h4 style="margin:0;">Изменить состав</h4>', 
            'id' => 'team'.$teamId.'-player-edit',
            'size' => 'modal-lg',
        ]);
        Modal::end();
    ?>
    <?php // Pjax::end(); ?>
</div>