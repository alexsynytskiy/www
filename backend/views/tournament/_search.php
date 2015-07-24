<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TournamentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tournament-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'command_id') ?>

    <?= $form->field($model, 'championship_id') ?>

    <?= $form->field($model, 'season_id') ?>

    <?= $form->field($model, 'played') ?>

    <?php // echo $form->field($model, 'won') ?>

    <?php // echo $form->field($model, 'draw') ?>

    <?php // echo $form->field($model, 'lost') ?>

    <?php // echo $form->field($model, 'goals_for') ?>

    <?php // echo $form->field($model, 'goals_against') ?>

    <?php // echo $form->field($model, 'points') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'fair_play') ?>

    <?php // echo $form->field($model, 'league_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
