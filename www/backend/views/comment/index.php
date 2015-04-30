<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\DatePicker;


/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комментарии';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'options' => ['width' => '100'],
            ],
            [
                'attribute' => 'user.username',
                'label' => 'Автор',
                'options' => ['width' => '120'],
                'value' => function($model) {
                    return Html::a($model->user->username, ['module/user/admin/view/'.$model->user_id]);
                },
                'format' => 'html',
            ],
            'content:html',
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return date('d.m.Y h:i', strtotime($model->created_at));
                },
                'format' => 'text',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'removeButton' => false,
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'autoclose' => true,
                    ]
                ]),
                'options' => ['width' => '160'],
            ],
            [
                'label' => 'Сущность',
                'value' => function($model) {
                    return $model->getCommentableLink();
                },
                'format' => 'html',
            ],
            // 'parent_id',
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70'],
            ],
        ],
    ]); ?>

</div>
