<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TopTagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Топовые теги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="top-tag-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить тег в топ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tag.name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
