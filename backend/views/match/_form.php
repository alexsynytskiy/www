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
use common\models\Championship;
use common\models\Arbiter;
use common\models\Command;
use common\models\Stadium;
use common\models\ChampionshipPart;
use common\models\Season;

/* @var $this yii\web\View */
/* @var $model common\models\Match */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="match-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'is_visible')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>
    
    <?= $form->field($model, 'is_finished')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>

    <?php 
        echo $form->field($model, 'championship_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Championship::find()->all(), 'id', 'name'),
            'language' => 'en',
            'options' => ['placeholder' => 'Выберите лигу...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

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

    <?php
        $availableChampionshipParts = [];
        
        if(!$model->isNewRecord) {
            $part = ChampionshipPart::findOne($model->championship_part_id);

            if(isset($season->id)) {
                $availableChampionshipParts = [$part->id => $part->name];
            }
        }

        echo $form->field($model, 'championship_part_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['championship_part/championship_part-list']),        
            'items' => $availableChampionshipParts,
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

    <?php
        $availableCommands = [];
        
        if(!$model->isNewRecord) {
            $team = Command::findOne($model->command_home_id);

            if(isset($season->id)) {
                $availableCommands = [$team->id => $team->name];
            }
        }

        echo $form->field($model, 'command_home_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['command/command-list']),        
            'items' => $availableCommands,
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

    <?php
        $availableCommands = [];
    
        if(!$model->isNewRecord) {
            $team = Command::findOne($model->command_guest_id);

            if(isset($season->id)) {
                $availableCommands = [$team->id => $team->name];
            }
        }

        echo $form->field($model, 'command_guest_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['command/command-list']),        
            'items' => $availableCommands,
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

    <?php
        $availableStadiums = [];
    
        if(!$model->isNewRecord) {
            $stadium = Stadium::findOne($model->stadium_id);

            if(isset($season->id)) {
                $availableStadiums = [$stadium->id => $stadium->name];
            }
        }

        echo $form->field($model, 'stadium_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['stadium/stadium-list']),        
            'items' => $availableStadiums,
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

    <?php

        $availableSeasons = [];
    
        if(!$model->isNewRecord) {
            $season = Season::findOne($model->season_id);

            if(isset($season->id)) {
                $availableSeasons = [$season->id => $season->name];
            }
        }

        echo $form->field($model, 'season_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['season/season-list']),        
            'items' => $availableSeasons,
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
        $availableArbiters = [];
        
        if(!$model->isNewRecord) {
            $arbiter = Arbiter::findOne($model->arbiter_main_id);

            if(isset($arbiter->id)) {
                $availableArbiters = [$arbiter->id => $arbiter->name];
            }
        }

        echo $form->field($model, 'arbiter_main_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['arbiter/arbiter-list']),        
            'items' => $availableArbiters,
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

    <?php
        $availableArbiters = [];
        
        if(!$model->isNewRecord) {
            $arbiter = Arbiter::findOne($model->arbiter_assistant_1_id);

            if(isset($arbiter->id)) {
                $availableArbiters = [$arbiter->id => $arbiter->name];
            }
        }

        echo $form->field($model, 'arbiter_assistant_1_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['arbiter/arbiter-list']),        
            'items' => $availableArbiters,
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

    <?php
        $availableArbiters = [];
        
        if(!$model->isNewRecord) {
            $arbiter = Arbiter::findOne($model->arbiter_assistant_2_id);

            if(isset($arbiter->id)) {
                $availableArbiters = [$arbiter->id => $arbiter->name];
            }
        }

        echo $form->field($model, 'arbiter_assistant_2_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['arbiter/arbiter-list']),        
            'items' => $availableArbiters,
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

    <?php
        $availableArbiters = [];
        
        if(!$model->isNewRecord) {
            $arbiter = Arbiter::findOne($model->arbiter_assistant_3_id);

            if(isset($arbiter->id)) {
                $availableArbiters = [$arbiter->id => $arbiter->name];
            }
        }

        echo $form->field($model, 'arbiter_assistant_3_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['arbiter/arbiter-list']),        
            'items' => $availableArbiters,
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

    <?php
        $availableArbiters = [];
        
        if(!$model->isNewRecord) {
            $arbiter = Arbiter::findOne($model->arbiter_assistant_4_id);

            if(isset($arbiter->id)) {
                $availableArbiters = [$arbiter->id => $arbiter->name];
            }
        }

        echo $form->field($model, 'arbiter_assistant_4_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['arbiter/arbiter-list']),        
            'items' => $availableArbiters,
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

    <?php
        $availableArbiters = [];
        
        if(!$model->isNewRecord) {
            $arbiter = Arbiter::findOne($model->arbiter_reserve_id);

            if(isset($arbiter->id)) {
                $availableArbiters = [$arbiter->id => $arbiter->name];
            }
        }

        echo $form->field($model, 'arbiter_reserve_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['arbiter/arbiter-list']),        
            'items' => $availableArbiters,
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

    <?php
        if($model->home_shots == null) {
            $model->home_shots = 0;
        }

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

    <?php
        if($model->guest_shots == null) {
            $model->guest_shots = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">40</b>';
        echo $form->field($model, 'guest_shots')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 40,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->home_shots_in == null) {
            $model->home_shots_in = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">40</b>';
        echo $form->field($model, 'home_shots_in')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 40,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->guest_shots_in == null) {
            $model->guest_shots_in = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">40</b>';
        echo $form->field($model, 'guest_shots_in')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 40,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->home_offsides == null) {
            $model->home_offsides = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">20</b>';
        echo $form->field($model, 'home_offsides')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 20,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->guest_offsides == null) {
            $model->guest_offsides = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">20</b>';
        echo $form->field($model, 'guest_offsides')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 20,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->home_corners == null) {
            $model->home_corners = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">30</b>';
        echo $form->field($model, 'home_corners')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 30,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->guest_corners == null) {
            $model->guest_corners = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">30</b>';
        echo $form->field($model, 'guest_corners')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 30,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->home_fouls == null) {
            $model->home_fouls = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">40</b>';
        echo $form->field($model, 'home_fouls')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 40,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->guest_fouls == null) {
            $model->guest_fouls = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">40</b>';
        echo $form->field($model, 'guest_fouls')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 40,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->home_yellow_cards == null) {
            $model->home_yellow_cards = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">28</b>';
        echo $form->field($model, 'home_yellow_cards')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 28,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->guest_yellow_cards == null) {
            $model->guest_yellow_cards = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">28</b>';
        echo $form->field($model, 'guest_yellow_cards')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 28,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->home_red_cards == null) {
            $model->home_red_cards = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">20</b>';
        echo $form->field($model, 'home_red_cards')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 20,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->guest_red_cards == null) {
            $model->guest_red_cards = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">20</b>';
        echo $form->field($model, 'guest_red_cards')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 20,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->home_goals == null) {
            $model->home_goals = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">15</b>';
        echo $form->field($model, 'home_goals')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 15,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>

    <?php
        if($model->guest_goals == null) {
            $model->guest_goals = 0;
        }

        echo '<b class="badge pull-left">0</b>';
        echo '<b class="badge pull-right">15</b>';
        echo $form->field($model, 'guest_goals')->widget(Slider::classname(), [
            'pluginOptions' => [
                'min' => 0,
                'max' => 15,
                'step' => 1,
                'tooltip' => 'always',
            ],
        ]);
    ?>    

    <?= $form->field($model, 'announcement')->widget(MarkdownEditor::classname()); ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
