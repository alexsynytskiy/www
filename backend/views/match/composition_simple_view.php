<?php

use yii\grid\GridView;

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
        <h3 class="panel-title">
            <i class="glyphicon glyphicon-list"></i> Состав команды
        </h3>
    </div>
    <?php
        echo GridView::widget([
            'summary' => '',
            'options' => ['class' => 'team-list'],
            'rowOptions' => function ($model, $index, $widget, $grid){
                return !$model->is_basis ? ['class' => 'danger'] : ['class' => 'success'];
            },
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn',
                    'options' => ['width' => '30', 'class' => 'text-center'],
                ],
                [
                    'label' => 'Игрок',
                    'value' => function($model) {
                        return isset($model->contract) ? $model->contract->name : null;
                    },
                ],
                'number',
//                [
//                    'label' => 'Амплуа',
//                    'value' => function($model) {
//                        return isset($model->contract->amplua) ? $model->contract->amplua->name : null;
//                    },
//                ],
            ],
        ]);   
    ?>
</div>