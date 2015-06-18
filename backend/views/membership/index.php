<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MembershipSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Состав клубов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="membership-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить игрока в клуб', ['create'], ['class' => 'btn btn-success']) ?>
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
                'options' => ['width' => '70'],
            ],
            [
                'label' => 'Команда',
                'attribute' => 'team.name',
                'value' => function($model) {
                    return $model->team->name;
                },
            ],
            [
                'label' => 'Игрок',
                'attribute' => 'player.name',
                'value' => function($model) {
                    return $model->player->name;
                },
            ],
            [
                'label' => 'Амплуа',
                'attribute' => 'amplua.name',
                'value' => function($model) {
                    return isset($model->amplua->name) ? $model->amplua->name : null;
                },
            ],
            'number',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
