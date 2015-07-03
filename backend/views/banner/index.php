<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\BannerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Баннеры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banner-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить баннер', ['create'], ['class' => 'btn btn-success']) ?>
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
            'name',
            [
                'attribute' => 'size',
                'value' => function($model) {
                    return $model->size ? 'Большой' : 'Маленький';
                },
                'filter' => [
                    0 => 'Маленький',
                    1 => 'Большой',
                ],
                'options' => ['width' => 160],
            ],
            [
                'attribute' => 'region',
                'value' => function($model) {
                    return $model->getRegionName();
                },
                'filter' => $searchModel::dropdownRegions(),
                'options' => ['width' => 160],
            ],
            'weight',
            [
                'attribute' => 'created_at',
                'value' => function($model) {
                    return date('d.m.Y H:i', strtotime($model->created_at));
                },
                'filter' => false,
                'options' => ['width' => 160],
            ],                

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => 70],
            ],
        ],
    ]); ?>

</div>
