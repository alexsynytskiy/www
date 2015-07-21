<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\StadiumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Стадионы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stadium-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить стадион', ['create'], ['class' => 'btn btn-success']) ?>
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
                'label' => 'Название',
                'options' => ['width' => '570'],
                'format' => 'html',
            ],
            [
                'attribute' => 'spectators',
                'label' => 'Вместимость',
                'options' => ['width' => '170'],
                'format' => 'html',
            ],
            [
                'attribute' => 'country.name',
                'label' => 'Страна',
                'options' => ['width' => '250'],
                'format' => 'html',
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
