<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\BannedIP */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banned-ip-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'start_ip_num_value')->widget(MaskedInput::classname(), [
        'mask' => '9[9][9].9[9][9].9[9][9].9[9][9]',
        ]) ?>

    <?= $form->field($model, 'end_ip_num_value')->widget(MaskedInput::classname(), [
        'mask' => '9[9][9].9[9][9].9[9][9].9[9][9]',
        ]) ?>

    <?= $form->field($model, 'is_active')->widget(CheckboxX::classname(), [
            'pluginOptions' => ['threeState' => false],
        ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
