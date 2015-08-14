<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use dosamigos\selectize\SelectizeDropDownList;

use common\models\League;
use common\models\Championship;
use common\models\Arbiter;
use common\models\Team;
use common\models\Stadium;
use common\models\ChampionshipPart;
use common\models\Season;

/* @var $this yii\web\View */
/* @var $model common\models\Match */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="match-form">


    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?php 
                echo $form->field($model, 'championship_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Championship::find()->all(), 'id', 'name'),
                    'language' => 'ru',            
                    'options' => ['placeholder' => 'Выберите турнир...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
        <div class="col-sm-6">
            <?php 
                echo $form->field($model, 'league_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(League::find()->all(), 'id', 'name'),
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Выберите лигу...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?php 
                echo $form->field($model, 'season_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(Season::find()->orderBy(['id' => SORT_DESC])->all(), 'id', 'name'),
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Выберите сезон...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
        <div class="col-sm-6">
            <?php 
                echo $form->field($model, 'championship_part_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(ChampionshipPart::find()->all(), 'id', 'name'),
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Выберите этап турнира...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?php
                $availableStadiums = [];
            
                if(!$model->isNewRecord) {
                    $stadium = Stadium::findOne($model->stadium_id);   
                    
                    if(isset($stadium->id)) {
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
                    ],
                ]);
            ?>
        </div>
        
        <div class="col-sm-6">
            <?php
                echo $form->field($model, 'date')->widget(DateTimePicker::classname(), [
                    'options' => ['placeholder' => 'Выберите дату матча'],
                    'removeButton' => false,
                    'language' => 'ru-RU',
                    'pluginOptions' => [
                        'language' => 'ru',
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy hh:ii'
                    ]
                ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?php
                $availableTeams = [];
                
                if(!$model->isNewRecord) {
                    $team = Team::findOne($model->command_home_id);
            
                    if(isset($team->id)) {
                        $availableTeams = [$team->id => $team->name];
                    }
                }
            
                echo $form->field($model, 'command_home_id')->widget(SelectizeDropDownList::classname(), [
                    'loadUrl' => Url::to(['team/team-list']),        
                    'items' => $availableTeams,
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
            <?php
                $availableTeams = [];
            
                if(!$model->isNewRecord) {
                    $team = Team::findOne($model->command_guest_id);
        
                    if(isset($team->id)) {
                        $availableTeams = [$team->id => $team->name];
                    }
                }
        
                echo $form->field($model, 'command_guest_id')->widget(SelectizeDropDownList::classname(), [
                    'loadUrl' => Url::to(['team/team-list']),        
                    'items' => $availableTeams,
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
    </div>

    <div class="row">
        <div class="col-sm-3 home-side">
            <?php if(isset($model->command_home_id) && isset($model->season_id)) { ?>
            <?= $this->render('composition_view', [
                'seasonId' => $model->season_id,
                'teamId' => $model->command_home_id,
                'team' => 'home',
                'compositionForm' => $compositionForm,
                'contractType' => $homeContractType,
                'composition' => $homeComposition, 
                'dataProvider' => $homeCompositionDataProvider,
            ]) ?>
            <?php } ?>
            
        </div>

        <div class="col-sm-3 guest-side">
            <?php if(isset($model->command_guest_id) && isset($model->season_id)) { ?>
            <?= $this->render('composition_view', [
                'seasonId' => $model->season_id,
                'teamId' => $model->command_guest_id,
                'team' => 'guest',
                'compositionForm' => $compositionForm,
                'contractType' => $guestContractType,
                'composition' => $guestComposition, 
                'dataProvider' => $guestCompositionDataProvider,
            ]) ?>
            <?php } ?>
        </div>

        <div class="col-sm-6">
            <?= $this->render('stat_form', compact('model', 'form')) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
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
                    ],
                ]);
            ?>
        </div>
        
        <div class="col-sm-6">
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
                    ],
                ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
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
                    ],
                ]);
            ?>
        </div>
        
        <div class="col-sm-6">
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
                    ],
                ]);
            ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
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
                    ],
                ]);
            ?>
        </div>
        
        <div class="col-sm-6">
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
                    ],
                ]);
            ?>
        </div>
    </div>

    <?php
    echo $form->field($model, 'announcement')->widget(\vova07\imperavi\Widget::className(), [
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

    <?= $form->field($model, 'is_visible')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>
    
    <?= $form->field($model, 'is_finished')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
