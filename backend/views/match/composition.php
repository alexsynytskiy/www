<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

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
            <button type="button" 
                    class="btn btn-xs btn-primary" 
                    title="Изменить" 
                    data-toggle="modal" 
                    data-target="#team<?= $teamId ?>-form">
                <i class="glyphicon glyphicon-pencil"></i>
            </button> 
        </div>
        <h3 class="panel-title">
            <i class="glyphicon glyphicon-list"></i> Состав команды
        </h3>
    </div>
    <?php
        Pjax::begin(['id' => 'team'.$teamId.'-composition']);
        echo GridView::widget([
            'summary' => '',
            'options' => ['class' => 'team-list'],
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'options' => ['width' => '30', 'class' => 'text-center'],
                ],
                [
                    'label' => 'Игрок',
                    'value' => function($model) {
                        $captain = $model->is_captain ? ' <span class="glyphicon glyphicon-king"></span>' : ''; 
                        return isset($model->contract) ? $model->contract->name.$captain : null;
                    },
                    'format' => 'html',
                ],
                'number',
                [
                    'attribute' => 'is_basis',
                    'value' => function($model) {
                        if($model->is_basis) return 'Да';
                        return 'Нет';
                    },
                ],
            ],
        ]);                   
    ?>
    <!-- Modal form -->
    <div class="modal fade" id="team<?= $teamId ?>-form" 
        tabindex="-1" role="dialog" 
        aria-labelledby="modal-team<?= $teamId ?>-label" 
        aria-hidden="true">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modal-team<?= $teamId ?>-label">
                        Изменить состав <span></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <?php
                    echo maksyutin\duallistbox\Widget::widget([
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
                        ]
                    ]);
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-primary">Сохранить изменения</button>
                </div>
            </div>
        </div>
    </div>
    <?php Pjax::end(); ?>
</div>