<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/**
 * @var $this yii\web\View
 * @var $dataProvider 
 * @var $answerForm 
 * @var $question 
 */
?>
<div class="panel panel-primary">
    <div class="panel-heading">
        <div class="pull-right">
            <?= Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                    'class' => 'btn btn-xs btn-primary modal-button', 
                    'title' => 'Добавить',
                    'data-url' => Url::to([
                        'question/create', 
                        'parent_id' => $question->id,
                    ]),
                    'data-target' => '#answer-create',
                ]) ?>
        </div>
        <h3 class="panel-title">
            <i class="glyphicon glyphicon-list"></i> Варианты ответов
        </h3>
    </div>
    <?php
        Pjax::begin([
            'id' => "answers-gridview", 
            'timeout' => false,
            'enablePushState' => false,
            'options' => ['class' => 'pjax-container'],
        ]);
        echo GridView::widget([
            'summary' => '',
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'options' => ['width' => '30', 'class' => 'text-center'],
                ],
                [
                    'label' => 'Ответ',
                    'attribute' => 'title',
                ],
                'voutes',
                'mark',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'options' => ['width' => 70],
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            $customUrl = Url::to(['question/delete', 'id' => $model['id']]);
                            return Html::button('<span class="glyphicon glyphicon-trash"></span>', [
                                'title' => 'Удалить', 
                                'class' => 'btn btn-xs btn-default delete-button',
                                'data-url' => $customUrl,
                            ]);
                        },
                        'update' => function ($url, $model) {
                            $customUrl = Url::to(['question/update', 'id' => $model['id']]);
                            return Html::button('<span class="glyphicon glyphicon-pencil"></span>', [
                                'title' => 'Изменить', 
                                'class' => 'btn btn-xs btn-default modal-button',
                                'data-url' => $customUrl,
                                'data-target' => '#answer-update',
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
            'header' => '<h4 style="margin:0;">Добавить ответ</h4>', 
            'id' => 'answer-create',
            'size' => 'modal-lg',
        ]);
        Modal::end();
    ?>
    <?php 
        Modal::begin([
            'header' => '<h4 style="margin:0;">Изменить ответ</h4>', 
            'id' => 'answer-update',
            'size' => 'modal-lg',
        ]);
        Modal::end();
    ?>
    <?php // Pjax::end(); ?>
</div>