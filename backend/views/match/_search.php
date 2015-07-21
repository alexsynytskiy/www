<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\MatchSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="match-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'is_visible') ?>

    <?= $form->field($model, 'championship_id') ?>

    <?= $form->field($model, 'command_home_id') ?>

    <?= $form->field($model, 'command_guest_id') ?>

    <?php // echo $form->field($model, 'stadium_id') ?>

    <?php // echo $form->field($model, 'season_id') ?>

    <?php // echo $form->field($model, 'round') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'arbiter_main_id') ?>

    <?php // echo $form->field($model, 'arbiter_assistant_1_id') ?>

    <?php // echo $form->field($model, 'arbiter_assistant_2_id') ?>

    <?php // echo $form->field($model, 'arbiter_reserve_id') ?>

    <?php // echo $form->field($model, 'home_shots') ?>

    <?php // echo $form->field($model, 'guest_shots') ?>

    <?php // echo $form->field($model, 'home_shots_in') ?>

    <?php // echo $form->field($model, 'guest_shots_in') ?>

    <?php // echo $form->field($model, 'home_offsides') ?>

    <?php // echo $form->field($model, 'guest_offsides') ?>

    <?php // echo $form->field($model, 'home_corners') ?>

    <?php // echo $form->field($model, 'guest_corners') ?>

    <?php // echo $form->field($model, 'home_fouls') ?>

    <?php // echo $form->field($model, 'guest_fouls') ?>

    <?php // echo $form->field($model, 'home_yellow_cards') ?>

    <?php // echo $form->field($model, 'guest_yellow_cards') ?>

    <?php // echo $form->field($model, 'home_red_cards') ?>

    <?php // echo $form->field($model, 'guest_red_cards') ?>

    <?php // echo $form->field($model, 'home_goals') ?>

    <?php // echo $form->field($model, 'guest_goals') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'championship_part_id') ?>

    <?php // echo $form->field($model, 'league_id') ?>

    <?php // echo $form->field($model, 'is_finished') ?>

    <?php // echo $form->field($model, 'announcement') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
