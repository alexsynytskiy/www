<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PlayerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Игроки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="player-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить игрока', ['create'], ['class' => 'btn btn-success']) ?>
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
            'firstname',
            'lastname',
            // 'slug',
            [
                'label' => 'Амплуа',
                'attribute' => 'amplua.name',
            ],
            // 'birthday',
            // 'height',
            // 'weight',
            // 'amplua_id',
            // 'country_id',
            // 'notes:ntext',
            // 'created_at',
            // 'updated_at',
            // 'image',
            // 'more_ampluas',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
