<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;

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
//            [
//                'attribute' => 'id',
//                'options' => ['width' => '100'],
//            ],
            [
                'attribute' => 'user.username',
                'label' => 'Автор',
                'options' => ['width' => '120'],
                'value' => function($model) {
                    return Html::a($model->getUserName(), ['/user/admin/view', 'id' => $model->user_id]);
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
            [
                'attribute' => 'tag.name',
                'label' => 'Теги',
                'value' => function($model) {
                    return $model->cached_tag_list;
                },
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
                'attribute' => 'content_category_id',
                'value' => function($model) {
                    return $model->getCategory();
                },
                'filter' => $searchModel::categoryDropdown(),
                'options' => ['width' => '110'],
            ],
            [
                'attribute' => 'is_public',
                'label' => 'Опубл.',
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
