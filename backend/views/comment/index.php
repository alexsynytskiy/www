<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комментарии';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="comment-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
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
            [
                'attribute' => 'id',
                'options' => ['width' => '100'],
            ],
            [
                'attribute' => 'profile.full_name',
                'label' => 'Автор',
                'options' => ['width' => '120'],
            ],
//            [
//                'attribute' => 'user.username',
//                'label' => 'Автор',
//                'options' => ['width' => '120'],
//                'value' => function($model) {
//                    if(isset($model->user)) {
//                        return Html::a($model->user->username, ['module/user/admin/view/' . $model->user_id]);
//                    } else {
//                        return 'Пользователь удален';
//                    }
//                },
//                'format' => 'html',
//            ],
            'content:html',
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
                'label' => 'Материал',
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
