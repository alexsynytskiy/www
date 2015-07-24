<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TournamentSettingsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tournament-settings-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'season_id') ?>

    <?= $form->field($model, 'scored_missed_weight') ?>

    <?= $form->field($model, 'goal_scored_weight') ?>

    <?= $form->field($model, 'goal_missed_weight') ?>

    <?php // echo $form->field($model, 'win_weight') ?>

    <?php // echo $form->field($model, 'draw_weight') ?>

    <?php // echo $form->field($model, 'defeat_weight') ?>

    <?php // echo $form->field($model, 'cl_positions') ?>

    <?php // echo $form->field($model, 'el_positions') ?>

    <?php // echo $form->field($model, 'reduction_positions') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
