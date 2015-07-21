<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArbiterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Арбитры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="arbiter-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить арбитра', ['create'], ['class' => 'btn btn-success']) ?>
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
                'options' => ['width' => '80'],
            ],
            [
                'attribute' => 'name',
                'label' => 'Имя',
                'options' => ['width' => '570'],
                'format' => 'html',
            ],
            [
                'attribute' => 'country.name',
                'label' => 'Страна',
                'options' => ['width' => '350'],
                'format' => 'html',
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
