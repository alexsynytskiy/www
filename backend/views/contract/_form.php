<?php

use common\models\Season;
use common\models\Amplua;

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\checkbox\CheckboxX;
use dosamigos\selectize\SelectizeDropDownList;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Contract */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contract-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        $availablePlayers = [];
        
        if(!$model->isNewRecord) {
            if(isset($model->player->id)) {
                $availablePlayers = [
                    $model->player->id => $model->player->name,
                ];
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
        $availableTeams = [];
        $team = $model->team;
        if(isset($team->id)) {
            $availableTeams = [$team->id => $team->name];
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

    <?php 
        echo $form->field($model, 'season_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Season::find()->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите сезон...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?php 
        echo $form->field($model, 'amplua_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Amplua::find()->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите амплуа...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?= $form->field($model, 'number')->textInput() ?>

    <?php
        $availableTeams = [];
        
        if(!$model->isNewRecord) {
            $team = $model->teamFrom;
            if(isset($team->id)) {
                $availableTeams = [$team->id => $team->name];
            }
        }

        echo $form->field($model, 'command_from_id')->widget(SelectizeDropDownList::classname(), [
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

    <?= $form->field($model, 'year_from')->textInput() ?>

    <?= $form->field($model, 'year_till')->textInput() ?>

    <?= $form->field($model, 'matches')->textInput() ?>

    <?= $form->field($model, 'goals')->textInput() ?>

    <?= $form->field($model, 'debut')->textInput() ?>
    
    <?= $form->field($model, 'is_active')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
