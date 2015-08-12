<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use yii\web\JsExpression;
use kartik\file\FileInput;
use dosamigos\selectize\SelectizeDropDownList;
use kartik\datetime\DateTimePicker;

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
//         $url = \yii\helpers\Url::to(['user/admin/user-list']);

//         $initScript = <<< SCRIPT
//             function (element, callback) {
//                 var id=\$(element).val();
//                 if (id !== "") {
//                     \$.ajax("{$url}?id=" + id, {
//                         dataType: "json"
//                     }).done(function(data) { callback(data.results);});
//                 }
//             }
// SCRIPT;

//         echo $form->field($model, 'user_id')->widget(Select2::classname(), [
//         'language' => 'ru',
//         'options' => ['placeholder' => 'Выберите пользователя'],
//         'pluginOptions' => [
//             'allowClear' => true,
//             'minimumInputLength' => 3,
//             'ajax' => [
//                 'url' => $url,
//                 'dataType' => 'json',
//                 'data' => new JsExpression('function(term,page) { return {search:term}; }'),
//                 'results' => new JsExpression('function(data,page) { return {results:data.results}; }'),
//             ],
//             'initSelection' => new JsExpression($initScript),
//         ],
//     ]); 
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'content')->widget(\vova07\imperavi\Widget::className(), [
        'plugins' => [
            'quote' => 'backend\assets\EditorAssetBundle',
            'skip' => 'backend\assets\EditorAssetBundle',
        ],
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'imageUpload' => \yii\helpers\Url::to(['/site/image-upload']),
            'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted',
                'unorderedlist', 'orderedlist', 'outdent', 'indent',
                'image', 'link', 'alignment', 'quote', 'skip'],
            'plugins' => [
                'fullscreen',
                'table',
                'video',
                'fontcolor',
            ],
//            'linkNofollow' => true,
//            'cleanOnPaste' => false,
//            'convertLinks' => false,
            'deniedTags' => ['style'],
            'replaceDivs' => false,
        ],
    ]); ?>

    
    <div class="row">
        <div class="col-sm-6">
            <?php
            $source = [];
            if(!$model->isNewRecord) {
                if(isset($model->source_title) && trim($model->source_title) != '') {
                    $source = [
                        0 => $model->source_title,
                    ];
                }
            }
            echo $form->field($model, 'source_id')->widget(SelectizeDropDownList::classname(), [
                'loadUrl' => Url::to(['source/source-list']),        
                'items' => $source,
                'options' => [
                    'multiple' => false,
                ],
                'clientOptions' => [
                    'valueField' => 'value',
                    'labelField' => 'text',
                    'persist' => false,
                ],
            ]);
            ?>
        </div>
        <div class="col-sm-6">
            <div id="source-url"><?= $model->source_url ?></div>
        </div>
    </div>
    

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
    if(!$model->isNewRecord) {
        $model->created_at = date('d.m.Y H:i', strtotime($model->created_at));
        echo $form->field($model, 'created_at')->widget(DateTimePicker::classname(), [
            'options' => ['placeholder' => 'Время создания поста'],
            'removeButton' => false,
            'language' => 'ru-RU',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy hh:ii'
            ]
        ]);
    }
    ?>

    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <a href="javascript:void(0)" class="spoiler-trigger pull-left" data-toggle="collapse">Привязать к матчу</a>
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

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'is_public')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>
            
            <?= $form->field($model, 'is_index')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
            
            <?= $form->field($model, 'is_top')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
            
            <?= $form->field($model, 'with_video')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
            
            <?= $form->field($model, 'with_photo')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
            
            <?= $form->field($model, 'allow_comment')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
        </div>

        <div class="col-sm-6">
            <?= $form->field($model, 'is_pin')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
            
            <?= $form->field($model, 'is_yandex_rss')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
            <?= $form->field($model, 'is_vk_rss')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
            <?= $form->field($model, 'is_fb_rss')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
            <?= $form->field($model, 'is_tw_rss')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState'=>false]]) ?>
            <?php if(isset($model->content_category_id) && $model->content_category_id == $model::CATEGORY_BLOG) { ?>
                <?= $form->field($model, 'selected_blog')
                        ->widget(CheckboxX::classname(), 
                        ['pluginOptions'=>['threeState'=>false]]) ?>
            <?php } ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
