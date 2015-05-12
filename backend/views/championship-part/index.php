<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ChampionshipPartSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Championship Parts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="championship-part-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Championship Part', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'championship_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
