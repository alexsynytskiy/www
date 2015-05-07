<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArbiterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Arbiters';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="arbiter-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Arbiter', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'country_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
