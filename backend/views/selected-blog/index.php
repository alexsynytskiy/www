<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\SelectedBlogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Избранные блоги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="selected-blog-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить выбор редакции', ['create'], ['class' => 'btn btn-success']) ?>
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
            ['class' => 'yii\grid\SerialColumn'],

            'post.title',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
