<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Country */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="country-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
    
    <?php
    $pluginOptions = [
        'showUpload' => false,
        'showRemove' => false,
        'overwriteInitial' => true,
        'browseLabel' => "Обзор...",
        'allowedFileExtensions' => ['jpg', 'gif', 'png'],
    ];
    if (isset($image) && $image->getFileUrl())
    {
        $pluginOptions['initialPreview'] = [
            Html::img($image->getFileUrl()),
        ];
    }
    echo $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => false,
        ],
        'pluginOptions' => $pluginOptions,
    ]);
    // echo $form->field($model, 'cropData')->hiddenInput(['id' => 'crop-data'])->label(false);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
