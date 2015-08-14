<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ClaimSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Жалобы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="claim-index">

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
            'id',
            [
                'attribute' => 'comment_id',
                'value' => function($model) {
                    if(!isset($model->comment)) return 'Удален';
                    $link = Html::a('#'.$model->comment_id, ['/comment/view', 'id' => $model->comment_id]);
                    return $link.' '.$model->comment->getShortContent(100, 200);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'user.username',
                'label' => 'Пользователь',
                'options' => ['width' => '120'],
                'value' => function($model) {
                    $username = isset($model->user) ? $model->user->username : '-';
                    return Html::a($username, ['/user/admin/view', 'id' => $model->user_id]);
                },
                'format' => 'html',
            ],
            [
                'attribute' => 'commentAuthor.username',
                'label' => 'Автор',
                'options' => ['width' => '120'],
                'value' => function($model) {
                    $username = isset($model->commentAuthor) ? $model->commentAuthor->username : '-';
                    return Html::a($username, ['/user/admin/view', 'id' => $model->user_id]);
                },
                'format' => 'html',
            ],
            'message',
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
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '50'],
                'template' => '{view} {delete}',
            ],
        ],
    ]); ?>

</div>
