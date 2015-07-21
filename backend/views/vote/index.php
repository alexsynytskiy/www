<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\VoteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Голосование';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // echo Html::a('Добавить голос', ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'vote',
            [
                'attribute' => 'voteable_type',
                'value' => function($model) {
                    return $model->getVoteableType();
                },
                'filter' => $searchModel::voteableTypeDropdown(),
                'options' => ['width' => '150'],
            ],
            'voteable_id',
            [
                'attribute' => 'user.username',
                'options' => ['width' => '200'],
                'value' => function($model) {
                    return Html::a($model->user->username, ['module/user/admin/view/'.$model->user_id]);
                },
                'format' => 'html',
            ],

            // 'ip_address',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
