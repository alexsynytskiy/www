<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use dosamigos\selectize\SelectizeDropDownList;
use common\models\Player;
use common\models\Team;
use common\models\Season;
use common\models\League;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Career */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="career-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        $availablePlayers = [];
        
        if(isset($model->player_id)) {
            $player = Player::findOne($model->player_id);

            if(isset($player->id)) {
                $availablePlayers = [$player->id => $player->name];
            }
        }

        echo $form->field($model, 'player_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['player/player-list']),        
            'items' => $availablePlayers,
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

    <?php 
        echo $form->field($model, 'league_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(League::find()->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => [
                'placeholder' => 'Выберите лигу...',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?php 
        echo $form->field($model, 'season_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Season::find()->orderBy(['id' => SORT_DESC])->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите сезон...',
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?php
        $availableTeams = [];
        
        if(!$model->isNewRecord) {
            $team = Team::findOne($model->command_id);
    
            if(isset($team->id)) {
                $availableTeams = [$team->id => $team->name];
            }
        }
    
        echo $form->field($model, 'command_id')->widget(SelectizeDropDownList::classname(), [
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

    <div class="col-sm-6">
        <?php
            if($model->championship_matches == null) {
                $model->championship_matches = 0;
            }
            echo $form->field($model, 'championship_matches')->textInput();
        ?>
    </div>

    <div class="col-sm-6">
        <?php
            if($model->championship_goals == null) {
                $model->championship_goals = 0;
            }
            echo $form->field($model, 'championship_goals')->textInput();
        ?>
    </div>

    <div class="col-sm-6">
        <?php
            if($model->cup_matches == null) {
                $model->cup_matches = 0;
            }
            echo $form->field($model, 'cup_matches')->textInput();
        ?>
    </div>

    <div class="col-sm-6">
        <?php
            if($model->cup_goals == null) {
                $model->cup_goals = 0;
            }
            echo $form->field($model, 'cup_goals')->textInput();
        ?>
    </div>

    <div class="col-sm-6">
        <?php
            if($model->euro_matches == null) {
                $model->euro_matches = 0;
            }
            echo $form->field($model, 'euro_matches')->textInput();
        ?>
    </div>

    <div class="col-sm-6">
        <?php
            if($model->euro_goals == null) {
                $model->euro_goals = 0;
            }
            echo $form->field($model, 'euro_goals')->textInput();
        ?>
    </div>

    <?= $form->field($model, 'avg_mark')->textInput() ?>

    <?= $form->field($model, 'goal_passes')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
