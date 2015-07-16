<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TournamentSettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tournament-settings-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'season_id')->textInput() ?>

    <?= $form->field($model, 'scored_missed_weight')->textInput() ?>

    <?= $form->field($model, 'goal_scored_weight')->textInput() ?>

    <?= $form->field($model, 'goal_missed_weight')->textInput() ?>

    <?= $form->field($model, 'win_weight')->textInput() ?>

    <?= $form->field($model, 'draw_weight')->textInput() ?>

    <?= $form->field($model, 'defeat_weight')->textInput() ?>

    <?= $form->field($model, 'cl_positions')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'el_positions')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reduction_positions')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
