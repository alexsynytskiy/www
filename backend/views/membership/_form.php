<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use dosamigos\selectize\SelectizeDropDownList;

use common\models\Amplua;

/* @var $this yii\web\View */
/* @var $model common\models\Membership */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="membership-form">

    <?php $form = ActiveForm::begin(); ?>

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
            ],
            'clientOptions' => [
                'valueField' => 'value',
                'labelField' => 'text',
                'persist' => false,
            ],
        ]);
    ?>

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


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
