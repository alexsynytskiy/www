<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CareerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="career-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'player_id') ?>

    <?= $form->field($model, 'league_id') ?>

    <?= $form->field($model, 'season_id') ?>

    <?= $form->field($model, 'command_id') ?>

    <?php // echo $form->field($model, 'championship_matches') ?>

    <?php // echo $form->field($model, 'championship_goals') ?>

    <?php // echo $form->field($model, 'cup_matches') ?>

    <?php // echo $form->field($model, 'cup_goals') ?>

    <?php // echo $form->field($model, 'euro_matches') ?>

    <?php // echo $form->field($model, 'euro_goals') ?>

    <?php // echo $form->field($model, 'avg_mark') ?>

    <?php // echo $form->field($model, 'goal_passes') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
