<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\file\FileInput;
use kartik\widgets\Typeahead;
use dosamigos\selectize\SelectizeDropDownList;

/* @var $this yii\web\View */
/* @var $model common\models\VideoPost */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="video-post-form">

    <?php $form = ActiveForm::begin(['options' => [
        'autocomplete' => 'off',
        'enctype' => 'multipart/form-data',
    ]]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'content')->widget(\vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'imageUpload' => \yii\helpers\Url::to(['/site/image-upload']),
            'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted',
                'unorderedlist', 'orderedlist', 'outdent', 'indent',
                'image', 'link', 'alignment'],
            'plugins' => [
                'quote',
                'skip',
                'fullscreen',
                'table',
                'video',
                'fontcolor',
            ],
        ],
    ]); ?>

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
        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png'],
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
    ?>

    <?php
    $pluginOptions = [
        'showUpload' => false,
        'showRemove' => false,
        'overwriteInitial' => true,
        'browseLabel' => "Обзор...",
        'allowedFileExtensions' => ['mp4', 'avi'],
        'previewSettings' => [
            'video' => ['width' => "500px", 'height' => "auto"],
        ],
    ];
    if (isset($videoAsset) && $videoAsset->getFileUrl())
    {
        $pluginOptions['initialPreview'] = [
            '<video src="'.$videoAsset->getFileUrl().'" controls></video>',
        ];
    }
    echo $form->field($model, 'video')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'video/*',
            'multiple' => false,
        ],
        'pluginOptions' => $pluginOptions,
    ]);
    ?>

    <?= $form->field($model, 'is_public')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>

    <?= $form->field($model, 'is_pin')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
