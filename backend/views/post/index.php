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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
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
            // 'slug',
            // 'content:ntext',
            [
                'attribute' => 'created_at',
                'value' => 'created_at',
                'format' => 'text',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'removeButton' => false,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true,
                    ]
                ]),
                'options' => ['width' => '160'],
            ],
            // 'updated_at',
            // 'is_top',
            // 'is_video',
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
            // 'is_cover',
            // 'is_index',
            // 'source_title',
            // 'source_url:url',
            // 'photo_id',
            // 'is_yandex_rss',
            // 'cached_tag_list',
            // 'allow_comment',

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70'],
            ],
        ],
    ]); ?>

</div>
