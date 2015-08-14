<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model common\models\Question */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="question-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'parent_id')->hiddenInput()->label(false) ?>

    <?php if(is_null($model->parent_id)) { ?>
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'is_active')->widget(CheckboxX::classname(), [
                    'pluginOptions' => ['threeState' => false],
                    'options' => [
                        'id' => 'team'.$model->id.'-is_active'
                    ],
                ]) ?>
            </div>
            <?php if($model->isNewRecord) { ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'is_float')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>
                </div>
            <?php } ?>
            <?php if($model->isNewRecord || !$model->is_float) { ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'is_multipart')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
