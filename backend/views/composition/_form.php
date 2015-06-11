<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Composition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="composition-form">

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

    <?= $form->field($model, 'number')->textInput() ?>
    <?= $form->field($model, 'is_basis')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>
    <?= $form->field($model, 'is_substitution')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>
    <?= $form->field($model, 'is_captain')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
