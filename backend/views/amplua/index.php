<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\AmpluaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Амплуа';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="amplua-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить амплуа', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'name',
            'abr',
            'line',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
