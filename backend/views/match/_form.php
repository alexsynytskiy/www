<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use kartik\widgets\DatePicker;
use kartik\slider\Slider;
use kartik\markdown\MarkdownEditor;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\Typeahead;
use yii\helpers\Url;
use dosamigos\selectize\SelectizeDropDownList;
use yii\web\JsExpression;


use common\models\League;


/* @var $this yii\web\View */
/* @var $model common\models\Match */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="match-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'is_visible')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>
    
    <?= $form->field($model, 'is_finished')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>

    <?= $form->field($model, 'championship_id')->textInput() ?>

    <?= $form->field($model, 'command_home_id')->textInput() ?>

    <?= $form->field($model, 'command_guest_id')->textInput() ?>

    <?= $form->field($model, 'stadium_id')->textInput() ?>

    <?= $form->field($model, 'season_id')->textInput() ?>

    <?= $form->field($model, 'round')->textInput(['maxlength' => 50]) ?>

    <?php
    echo $form->field($model, 'date')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Выберите дату матча'],
        'removeButton' => false,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy'
        ]
    ]);
    ?>

    <?php
    // echo $form->field($model, 'arbiter_main_id')->widget(Typeahead::classname(), [
    //     'options' => ['placeholder' => 'Поиск арбитра при вводе ...'],
    //     'pluginOptions' => ['highlight' => true],
    //     'dataset' => [
    //         [
    //             'remote' => Url::to(['arbiter/arbiter-list']) . '?q=%QUERY',
    //             'limit' => 10,
    //         ]
    //     ]
    // ]);

    echo $form->field($model, 'arbiter_main_id')->widget(SelectizeDropDownList::classname(), [
        'loadUrl' => Url::to(['arbiter/arbiter-list']),
        'items' => [$model->arbiter_main_id => 'ssasd saa'],
        'options' => [
            'multiple' => false,
        ],
        'clientOptions' => [
            'valueField' => 'value',
            'labelField' => 'text',
            'persist' => false,
            'createOnBlur' => true,
            'create' => new JsExpression('function(input) { return { value: "{new}" + input, text: input } }'),
        ],
    ]);
    ?>

    <?= $form->field($model, 'arbiter_assistant_1_id')->textInput() ?>

    <?= $form->field($model, 'arbiter_assistant_2_id')->textInput() ?>

    <?= $form->field($model, 'arbiter_reserve_id')->textInput() ?>

    <?php
    echo '<b class="badge pull-left">0</b>';
    echo '<b class="badge pull-right">40</b>';
    echo $form->field($model, 'home_shots')->widget(Slider::classname(), [
        'pluginOptions' => [
            'min' => 0,
            'max' => 40,
            'step' => 1,
            'tooltip' => 'always',
        ],
    ]);
    ?>

    <?= $form->field($model, 'guest_shots')->textInput() ?>

    <?= $form->field($model, 'home_shots_in')->textInput() ?>

    <?= $form->field($model, 'guest_shots_in')->textInput() ?>

    <?= $form->field($model, 'home_offsides')->textInput() ?>

    <?= $form->field($model, 'guest_offsides')->textInput() ?>

    <?= $form->field($model, 'home_corners')->textInput() ?>

    <?= $form->field($model, 'guest_corners')->textInput() ?>

    <?= $form->field($model, 'home_fouls')->textInput() ?>

    <?= $form->field($model, 'guest_fouls')->textInput() ?>

    <?= $form->field($model, 'home_yellow_cards')->textInput() ?>

    <?= $form->field($model, 'guest_yellow_cards')->textInput() ?>

    <?= $form->field($model, 'home_red_cards')->textInput() ?>

    <?= $form->field($model, 'guest_red_cards')->textInput() ?>

    <?= $form->field($model, 'home_goals')->textInput() ?>

    <?= $form->field($model, 'guest_goals')->textInput() ?>

    <?= $form->field($model, 'championship_part_id')->textInput() ?>

    <?php 
    echo $form->field($model, 'league_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(League::find()->all(), 'id', 'name'),
        'language' => 'en',
        'options' => ['placeholder' => 'Выберите лигу...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?= $form->field($model, 'announcement')->widget(MarkdownEditor::classname()); ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
