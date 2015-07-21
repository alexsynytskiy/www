<?php

use common\models\TransferType;
use common\models\Season;

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
/* @var $model common\models\Transfer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transfer-form">

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
        echo $form->field($model, 'transfer_type_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(TransferType::find()->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите тип трансфера...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

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

    <?php
        $availableTeams = [];
        
        if(!$model->isNewRecord) {
            $team = $model->teamTo;
            if(isset($team->id)) {
                $availableTeams = [$team->id => $team->name];
            }
        }

        echo $form->field($model, 'command_to_id')->widget(SelectizeDropDownList::classname(), [
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

    <?= $form->field($model, 'clubs')->textInput(['maxlength' => 255]) ?>

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

    <?= $form->field($model, 'probability', ['template' => '
           {label}
           <div class="input-group col-sm-4 ">
              <span class="input-group-addon">
                 %
              </span>
              {input}
           </div>
           {error}{hint}'])->textInput(['data-default' => '0']) ?>

    <?= $form->field($model, 'sum')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'is_active')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>


    <?php
        echo $form->field($model, 'contract_date')->widget(DatePicker::classname(), [
            'options' => ['placeholder' => 'Выберите день подписания контракта'],
            'removeButton' => false,
            'language' => 'ru-RU',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ]
        ]);
    ?>

    <?= $form->field($model, 'others')->textInput(['maxlength' => 255]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
