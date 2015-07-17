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

    <?= $form->field($model, 'is_public')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>

    <?= $form->field($model, 'is_pin')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
