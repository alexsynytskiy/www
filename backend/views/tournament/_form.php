<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use dosamigos\selectize\SelectizeDropDownList;

use common\models\Championship;
use common\models\League;
use common\models\Season;

/* @var $this yii\web\View */
/* @var $model common\models\Tournament */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tournament-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="row">
        <div class="col-sm-6">
        <?php
            $availableTeams = [];
            
            if(!$model->isNewRecord) {
                $team = $model->team;
                if(isset($team->id)) {
                    $availableTeams = [$team->id => $team->name];
                }
            }
        
            echo $form->field($model, 'command_id')->widget(SelectizeDropDownList::classname(), [
                'loadUrl' => Url::to(['team/team-list']),        
                'items' => $availableTeams,
                'options' => [
                    'multiple' => false,
                    'placeholder' => 'Выберите команду...'
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
            echo $form->field($model, 'championship_id')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Championship::find()->all(), 'id', 'name'),
                'language' => 'ru',
                'options' => ['placeholder' => 'Выберите чемпионат...'],
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
        
        <div class="col-sm-6">
        <?php 
            $seasons = ArrayHelper::map(Season::find()->where(['>', 'id', 42])->all(), 'id', 'name');
            foreach ($seasons as $key => $season) {
               if (strpos($season, '/') === false) {
                   unset($seasons[$key]);
               }
            }
            echo $form->field($model, 'season_id')->widget(Select2::classname(), [
                'data' => $seasons,
                'language' => 'ru',
                'options' => ['placeholder' => 'Выберите сезон...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);
        ?>
        </div>
    </div>

    <div class="row">

        <div class="col-sm-3"><?= $form->field($model, 'won')->textInput() ?></div>

        <div class="col-sm-3"><?= $form->field($model, 'lost')->textInput() ?></div>
        
        <div class="col-sm-3"><?= $form->field($model, 'draw')->textInput() ?></div>

        <div class="col-sm-3"><?= $form->field($model, 'penalty_points')->textInput() ?></div>

    </div>    
    <div class="row">

        <div class="col-sm-3"><?= $form->field($model, 'goals_for')->textInput() ?></div>

        <div class="col-sm-3"><?= $form->field($model, 'goals_against')->textInput() ?></div>

        <div class="col-sm-3"><?= $form->field($model, 'weight')->textInput() ?></div>

    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
