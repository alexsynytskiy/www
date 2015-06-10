<?php

use common\models\TransferType;
use common\models\Season;

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel common\models\TransferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Трансферы';
$this->params['breadcrumbs'][] = $this->title;

$transferTable = $searchModel::tableName();
$seasonTable = Season::tableName();
$seasons = Season::find()
    ->innerJoin($transferTable, "{$transferTable}.season_id = {$seasonTable}.id")
    ->all();
$seasonFilter = ArrayHelper::map($seasons, 'id', 'name');
?>
<div class="transfer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить трансфер', ['create'], ['class' => 'btn btn-success']) ?>
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
                'label' => 'Игрок',
                'attribute' => 'player.lastname',
                'value' => function($model) {
                    return $model->player->firstname.' '.$model->player->lastname;
                },
            ],
            [
                'attribute' => 'transfer_type_id',
                'value' => function($model) {
                    return $model->transferType->name;
                },
                'filter' => ArrayHelper::map(TransferType::find()->all(), 'id', 'name'),
            ],
            [
                'label' => 'С команды',
                'attribute' => 'teamFrom.name',
            ],
            [
                'label' => 'В команду',
                'attribute' => 'teamTo.name',
            ],
            [
                'attribute' => 'season_id',
                'value' => function($model) {
                    return $model->season->name;
                },
                'filter' => $seasonFilter,
                'options' => ['width' => '120'],
            ],
            // 'probability',
            'sum',
            [
                'attribute' => 'is_active',
                'value' => function($model) {
                    if($model->is_active) return 'Да';
                    return 'Нет';
                },
                'filter' => [
                    1 => 'Да',
                    0 => 'Нет',
                ],
            ],
            'clubs',
            // 'others',
            // 'contract_date',
            // 'created_at',
            // 'updated_at',
            // 'comments_count',

            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70'],
            ],
        ],
    ]); ?>

</div>
