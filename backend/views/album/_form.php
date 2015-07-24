<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use kartik\select2\Select2;
use yii\web\JsExpression;
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

    <?php
    echo $form->field($model, 'description')->widget(\vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'plugins' => [
                // 'clips',
                'fullscreen',
                'table',
                'video',
                'fontcolor',
            ]
        ]
    ]);
    ?>
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
            'overwriteInitial' => true,
            'browseLabel' => "Обзор...",
            'allowedFileExtensions' => ['jpg', 'gif', 'png'],
        ];

        if (isset($coverImage) && $coverImage->getFileUrl())
        {
            $pluginOptions['initialPreview'] = [
                Html::img($coverImage->getFileUrl()),
            ];
        }

        echo $form->field($model, 'coverImage')->widget(FileInput::classname(), [
            'options' => [
                'accept' => 'image/*',
                'multiple' => false,
            ],
            'pluginOptions' => $pluginOptions,
        ]);
    ?>

    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <a href="javascript:void(0)" class="spoiler-trigger pull-left" data-toggle="collapse">Привязять к матчу</a>
            <button type="button" class="spoiler-trigger pull-right btn btn-default btn-xs <?= isset($relation->match) ? 'dropup' : 'dropdown' ?>" data-toggle="collapse"><span class="caret"></span></button>
        </div>
        <div class="panel-collapse collapse <?= isset($relation->match) ? 'in' : 'out' ?>">
            <div class="panel-body">
                <?php 
                    echo $this->render('@backend/views/relation/relation', [
                        'form' => $form,
                        'post' => $model,
                        'relation' => $relation,
                        'matchModel' => $matchModel,
                        'matchesList' => $matchesList,
                    ]);
                ?>
            </div>
        </div>
    </div>

    <?php
    $pluginOptions = [
        'showUpload' => false,
        'showRemove' => false,
        'overwriteInitial' => false,
        'initialPreviewShowDelete' => true,
        'dropZoneEnabled' => false,
        'browseLabel' => "Обзор...",
        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png'],
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
    if(isset($images))
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
