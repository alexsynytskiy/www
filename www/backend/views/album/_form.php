<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\markdown\MarkdownEditor;
use kartik\file\FileInput;
use dosamigos\selectize\SelectizeDropDownList;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $images array Array of common\models\Asset */
/* @var $tags array Array of common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => [
        'autocomplete' => 'off',
        'enctype' => 'multipart/form-data',
    ]]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'description')->widget(MarkdownEditor::classname()); ?>

    <?php
    $availableTags = [];
    if(isset($tags))
    {
        foreach ($tags as $tag) {
            $availableTags[$tag->id] = $tag->name;
        }
    }

    echo $form->field($model, 'tags')->widget(SelectizeDropDownList::classname(), [
        'loadUrl' => '/admin/tag/tag-list',
        'items' => $availableTags,
        'options' => [
            'multiple' => true,
        ],
        'clientOptions' => [
            'delimiter' => ',',
            'valueField' => 'value',
            'labelField' => 'text',
            'persist' => false,
            'createOnBlur' => true,
            'maxItems' => 10,
            'create' => new JsExpression('function(input) { return { value: "{new}" + input, text: input } }'),
        ],
    ]);
    ?>

    <?php
    $pluginOptions = [
        'showUpload' => false,
        'showRemove' => false,
        'overwriteInitial' => false,
        'initialPreviewShowDelete' => true,
        'dropZoneEnabled' => false,
        'browseLabel' => "Обзор...",
        'allowedFileExtensions' => ['jpg', 'gif', 'png'],
        'uploadUrl' => "/admin/album/image-delete",
        'layoutTemplates' => [
            'actions' => "<div class='file-actions'>\n" .
            "    <div class='file-footer-buttons'>\n" .
            "        {delete}" .
            "    </div>\n" .
            "    <div class='clearfix'></div>\n" .
            "</div>",
        ],
    ];
    if (isset($images))
    {
        foreach ($images as $image) {
            if ($image->getFileUrl()) {
                $pluginOptions['initialPreview'][] = Html::img($image->getFileUrl());
                $pluginOptions['initialPreviewConfig'][] = [
                    'caption' => $image->filename,
                    'url' => '/admin/album/image-delete',
                    'key' => $image->id,
                ];
            }
        }
    }
    echo $form->field($model, 'images[]')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => true,
        ],
        'pluginOptions' => $pluginOptions,
    ]);
    echo $form->field($model, 'imagesData')->hiddenInput(['id' => 'images-data'])->label(false);
    ?>

    <?= $form->field($model, 'is_public')->widget(CheckboxX::classname(), ['pluginOptions' => ['threeState' => false]]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
