<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CompositionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Составы команд матчей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="composition-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
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
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            [
                'label' => 'Матч',
                'attribute' => 'match_id',
                'value' => function($model) {
                    return $model->match->name.' ('.$model->match_id.')';
                },
            ],
            [
                'label' => 'Команда',
                'attribute' => 'team.name',
                'value' => function($model) {
                    return $model->team->name;
                },
            ],
            [
                'label' => 'Игрок',
                'attribute' => 'contract_id',
                'value' => function($model) {
                    return isset($model->contract) ? $model->contract->name.' ('.$model->contract_id.')' : null;
                },
            ],
            'number',
            [
                    'label' => 'Амплуа',
                    'value' => function($model) {
                        return isset($model->contract->amplua) ? $model->contract->amplua->name : null;
                    },
                ],
            [
                'attribute' => 'is_substitution',
                'value' => function($model) {
                    if($model->is_substitution) return 'Да';
                    return 'Нет';
                },
                'filter' => [
                    1 => 'Да',
                    0 => 'Нет',
                ],
            ],
            [
                'attribute' => 'is_basis',
                'value' => function($model) {
                    if($model->is_basis) return 'Да';
                    return 'Нет';
                },
                'filter' => [
                    1 => 'Да',
                    0 => 'Нет',
                ],
            ],
            [
                'attribute' => 'is_captain',
                'value' => function($model) {
                    if($model->is_captain) return 'Да';
                    return 'Нет';
                },
                'filter' => [
                    1 => 'Да',
                    0 => 'Нет',
                ],
            ],
            // 'contract_type',

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70'],
            ],
        ],
    ]); ?>

</div>
