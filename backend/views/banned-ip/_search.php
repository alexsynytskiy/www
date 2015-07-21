<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BannedIPSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banned-ip-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'start_ip_num_value') ?>

    <?= $form->field($model, 'end_ip_num_value') ?>

    <?= $form->field($model, 'is_active') ?>

    <?php // echo $form->field($model, 'start_ip_num') ?>

    <?php // echo $form->field($model, 'end_ip_num') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
