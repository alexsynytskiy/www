<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Записи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width' => '100'],
            ],
            [
                'attribute' => 'user.username',
                'label' => 'Автор',
                'options' => ['width' => '120'],
                'value' => function($model) {
                    return Html::a($model->getUserName(), ['module/user/admin/view/'.$model->user_id]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'title',
                'value' => function($model) {
                    return Html::a($model->title, ['post/'.$model->id]);
                },
                'format' => 'html',
            ],
            // [
            //     'attribute' => 'created_at',
            //     'value' => function($model){
            //         return date('d.m.Y h:i', strtotime($model->created_at));
            //     },
            //     'format' => 'text',
            //     'filter' => DatePicker::widget([
            //         'model' => $searchModel,
            //         'attribute' => 'created_at',
            //         'removeButton' => false,
            //         'pluginOptions' => [
            //             'format' => 'dd.mm.yyyy',
            //             'autoclose' => true,
            //         ]
            //     ]),
            //     'options' => ['width' => '160'],
            // ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    return date('d.m.Y h:i', strtotime($model->updated_at));
                },
                'format' => 'text',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'updated_at',
                    'removeButton' => false,
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'autoclose' => true,
                    ]
                ]),
                'options' => ['width' => '160'],
            ],
            [
                'attribute' => 'content_category_id',
                'value' => function($model) {
                    return $model->getCategory();
                },
                'filter' => $searchModel::categoryDropdown(),
                'options' => ['width' => '110'],
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
            // 'comments_count',

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70'],
            ],
        ],
    ]); ?>

</div>
