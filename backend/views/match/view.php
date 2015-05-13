<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Match */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Матчи', 'url' => ['index']];
$this->params['breadcrumbs'][] =  $model->commandHome->name . ' - ' . $model->commandGuest->name;
?>
<div class="match-view">

    <h1><?= Html::encode($model->commandHome->name . ' - ' . $model->commandGuest->name) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить матч?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'is_visible',
                'value' => $model->is_visible ? 'Да' : 'Нет',
            ],
            'championship_id',
            
            'command_home_id',
            'command_guest_id',
            'stadium_id',
            'season_id',
            'round',
            'date',
            'arbiter_main_id',
            'arbiter_assistant_1_id',
            'arbiter_assistant_2_id',
            'arbiter_assistant_3_id',
            'arbiter_assistant_4_id',
            'arbiter_reserve_id',
            'home_shots',
            'guest_shots',
            'home_shots_in',
            'guest_shots_in',
            'home_offsides',
            'guest_offsides',
            'home_corners',
            'guest_corners',
            'home_fouls',
            'guest_fouls',
            'home_yellow_cards',
            'guest_yellow_cards',
            'home_red_cards',
            'guest_red_cards',
            'home_goals',
            'guest_goals',
            'comments_count',
            'created_at',
            'updated_at',
            'championship_part_id',
            'league_id',
            'is_finished',
            'announcement:ntext',
        ],
    ]) ?>

</div>
