<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TransferSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transfer-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'season_id') ?>

    <?= $form->field($model, 'transfer_type_id') ?>

    <?= $form->field($model, 'player_id') ?>

    <?= $form->field($model, 'probability') ?>

    <?php // echo $form->field($model, 'command_from_id') ?>

    <?php // echo $form->field($model, 'command_to_id') ?>

    <?php // echo $form->field($model, 'sum') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'clubs') ?>

    <?php // echo $form->field($model, 'others') ?>

    <?php // echo $form->field($model, 'contract_date') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
