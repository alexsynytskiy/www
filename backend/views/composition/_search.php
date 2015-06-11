<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CompositionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="composition-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'match_id') ?>

    <?= $form->field($model, 'contract_id') ?>

    <?= $form->field($model, 'is_substitution') ?>

    <?= $form->field($model, 'is_basis') ?>

    <?php // echo $form->field($model, 'number') ?>

    <?php // echo $form->field($model, 'is_captain') ?>

    <?php // echo $form->field($model, 'command_id') ?>

    <?php // echo $form->field($model, 'contract_type') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
