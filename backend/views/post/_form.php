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
/* @var $model common\models\Post */
/* @var $image common\models\Asset */
/* @var $tags array Array of common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => [
        'autocomplete' => 'off',
        'enctype' => 'multipart/form-data',
    ]]); ?>

    <?php
        $url = \yii\helpers\Url::to(['user/admin/user-list']);

        $initScript = <<< SCRIPT
            function (element, callback) {
                var id=\$(element).val();
                if (id !== "") {
                    \$.ajax("{$url}?id=" + id, {
                        dataType: "json"
                    }).done(function(data) { callback(data.results);});
                }
            }
SCRIPT;

        echo $form->field($model, 'user_id')->widget(Select2::classname(), [
        'language' => 'ru',
        'options' => ['placeholder' => 'Выберите пользователя'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(term,page) { return {search:term}; }'),
                'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
            ],
            'initSelection' => new JsExpression($initScript),
        ],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?php 
    echo $form->field($model, 'content')->widget(\vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'imageUpload' => \yii\helpers\Url::to(['/site/image-upload']),
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
    echo $form->field($model, 'source_title')->widget(Typeahead::classname(), [
        'options' => ['placeholder' => 'Поиск источника при вводе ...'],
        'pluginOptions' => [
            'highlight' => true,
            'imageUpload' => '/upload.php',
        ],
        'dataset' => [
            [
                'remote' => \yii\helpers\Url::to(['source/source-name-list']) . '?q=%QUERY',
                'limit' => 10,
            ]
        ]
    ]);
    ?>

    <?php
    echo $form->field($model, 'source_url')->widget(Typeahead::classname(), [
        'options' => ['placeholder' => 'Поиск источника при вводе ...'],
        'pluginOptions' => ['highlight'=>true],
        'dataset' => [
            [
                'remote' => \yii\helpers\Url::to(['source/source-url-list']) . '?q=%QUERY',
                'limit' => 10,
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

    <?= $form->field($model, 'content_category_id')->dropDownList($model::categoryDropdown()) ?>

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

    <?= $form->field($model, 'is_public')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>

    <?= $form->field($model, 'is_index')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
    
    <?= $form->field($model, 'is_top')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>

    <?= $form->field($model, 'is_pin')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
    
    <?= $form->field($model, 'with_video')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
    
    <?= $form->field($model, 'with_photo')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>

    <?= $form->field($model, 'is_yandex_rss')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>

    <?= $form->field($model, 'allow_comment')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
