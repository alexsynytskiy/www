<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SubscribingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подписка';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscribing-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить email в подписку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'id',
                'options' => ['width' => 80],
            ],
            'email',
            [
                'attribute' => 'created_at',
                'filter' => false,
                'value' => function($model) {
                    return date('d.m.Y', strtotime($model->created_at));
                },
                'options' => ['width' => 100],
            ],
            // [
            //     'attribute' => 'updated_at',
            //     'value' => function($model) {
            //         return date('d.m.Y', strtotime($model->updated_at));
            //     },
            // ],

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => 70],
            ],
        ],
    ]); ?>

</div>
