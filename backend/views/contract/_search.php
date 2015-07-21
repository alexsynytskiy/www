<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ContractSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'command_id') ?>

    <?= $form->field($model, 'season_id') ?>

    <?= $form->field($model, 'amplua_id') ?>

    <?= $form->field($model, 'contractable_type') ?>

    <?php // echo $form->field($model, 'contractable_id') ?>

    <?php // echo $form->field($model, 'number') ?>

    <?php // echo $form->field($model, 'command_from_id') ?>

    <?php // echo $form->field($model, 'year_from') ?>

    <?php // echo $form->field($model, 'year_till') ?>

    <?php // echo $form->field($model, 'matches') ?>

    <?php // echo $form->field($model, 'goals') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'debut') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
