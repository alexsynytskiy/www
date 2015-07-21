<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MainInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Информация о команде';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-info-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'title',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
