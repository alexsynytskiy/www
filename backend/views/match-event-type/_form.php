<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\MatchEventType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="match-event-type-form">

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
	        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'svg'],
	    ];
	    $icon = $model->getAsset();
	    if (!$model->isNewRecord && $icon->getFileUrl())
	    {
	        $pluginOptions['initialPreview'] = [
	            Html::img($icon->getFileUrl()),
	        ];
	    }
	    echo $form->field($model, 'icon')->widget(FileInput::classname(), [
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
