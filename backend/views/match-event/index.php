<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\MatchEventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Match Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="match-event-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Match Event', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'match_id',
            'match_event_type_id',
            'composition_id',
            'minute',
            // 'notes:ntext',
            // 'created_at',
            // 'updated_at',
            // 'substitution_id',
            // 'additional_minute',
            // 'is_hidden',
            // 'position',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
