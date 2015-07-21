<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Career */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="career-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'player_id')->textInput() ?>

    <?= $form->field($model, 'league_id')->textInput() ?>

    <?= $form->field($model, 'season_id')->textInput() ?>

    <?= $form->field($model, 'command_id')->textInput() ?>

    <?= $form->field($model, 'championship_matches')->textInput() ?>

    <?= $form->field($model, 'championship_goals')->textInput() ?>

    <?= $form->field($model, 'cup_matches')->textInput() ?>

    <?= $form->field($model, 'cup_goals')->textInput() ?>

    <?= $form->field($model, 'euro_matches')->textInput() ?>

    <?= $form->field($model, 'euro_goals')->textInput() ?>

    <?= $form->field($model, 'avg_mark')->textInput() ?>

    <?= $form->field($model, 'goal_passes')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
