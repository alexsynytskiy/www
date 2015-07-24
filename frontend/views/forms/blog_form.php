<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use kartik\file\FileInput;
use kartik\widgets\Typeahead;
use dosamigos\selectize\SelectizeDropDownList;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $image common\models\Asset */
/* @var $tags array Array of common\models\Tag */
/* @var $title string */
?>

<div class="default-box post-form-box">
    <div class="box-header">
        <div class="box-title"><?= $title ?></div>
    </div>
    <div class="box-content">

    <?php $form = ActiveForm::begin(['options' => [
        'class' => 'default-form',
        'autocomplete' => 'off',
        'enctype' => 'multipart/form-data',
    ]]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'content')->widget(\vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'imageUpload' => \yii\helpers\Url::to(['/site/image-upload']),
            'buttons' => ['formatting', 'bold', 'italic', 'underline', 'deleted',
                'unorderedlist', 'orderedlist', 'outdent', 'indent',
                'image', 'link'],
            'plugins' => [
                'quote',
                'skip',
                'table',
                'video',
                'fullscreen',
            ],
        ],
    ]); ?>

    <?php
    // echo $form->field($model, 'source_title')->widget(Typeahead::classname(), [
    //     'options' => ['placeholder' => 'Поиск источника при вводе ...'],
    //     'pluginOptions' => [
    //         'highlight' => true,
    //         'imageUpload' => '/upload.php',
    //     ],
    //     'dataset' => [
    //         [
    //             'remote' => \yii\helpers\Url::to(['source/source-name-list']) . '?q=%QUERY',
    //             'limit' => 10,
    //         ]
    //     ]
    // ]);
    ?>

    <?php
    // echo $form->field($model, 'source_url')->widget(Typeahead::classname(), [
    //     'options' => ['placeholder' => 'Поиск источника при вводе ...'],
    //     'pluginOptions' => ['highlight'=>true],
    //     'dataset' => [
    //         [
    //             'remote' => \yii\helpers\Url::to(['source/source-url-list']) . '?q=%QUERY',
    //             'limit' => 10,
    //         ]
    //     ]
    // ]);
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
    
    <div>
        <div class="field field-submit">
            <?= Html::submitInput($model->isNewRecord ? 'Добавить' : 'Изменить', []) ?>
        </div>
        <?php if(!$model->isNewRecord) { ?>
        <?= Html::a('Просмотр', $model->getUrl(), ['class' => 'field-button']) ?>
        <?php } ?>
        <div class="clearfix"></div>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
