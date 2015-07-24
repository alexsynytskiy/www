<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Country */
/* @var $form yii\widgets\ActiveForm */
/* @var $image common\models\Asset */
?>

<div class="country-form">

    <?php $form = ActiveForm::begin(['options' => [
        'autocomplete' => 'off',
        'enctype' => 'multipart/form-data',
    ]]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
    
    <?php
        $pluginOptions = [
            'showUpload' => false,
            'showRemove' => false,
            'overwriteInitial' => true,
            'browseLabel' => "Обзор...",
            'allowedFileExtensions' => ['jpg', 'gif', 'png'],
        ];

        if (isset($flag) && $flag->getFileUrl())
        {
            $pluginOptions['initialPreview'] = [
                Html::img($flag->getFileUrl()),
            ];
        }

        echo $form->field($model, 'flag')->widget(FileInput::classname(), [
            'options' => [
                'accept' => 'image/*',
                'multiple' => false,
            ],
            'pluginOptions' => $pluginOptions,
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
