<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BannedIPSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заблокированные IP адреса';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banned-ip-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить IP адрес', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'ip_address',
                'value' => function($model) {
                    $ip = $model->start_ip_num_value;
                    if(isset($model->end_ip_num_value) && trim($model->end_ip_num_value) != '') {
                        $ip .= ' - '.$model->end_ip_num_value;
                    }
                    return $ip;
                },
                'options' => ['width' => '230'],
            ],
            [
                'attribute' => 'is_active',
                'value' => function($model) {
                    if($model->is_active) return 'Да';
                    return 'Нет';
                },
                'filter' => [
                    1 => 'Да',
                    0 => 'Нет',
                ],
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
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70'],
            ],
        ],
    ]); ?>

</div>
