<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use kartik\checkbox\CheckboxX;
use kartik\select2\Select2;

use common\models\Match;
use common\models\MatchEventType;
use common\models\Composition;
/* @var $this yii\web\View */
/* @var $model common\models\MatchEvent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="match-event-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6">
            <?php 
                echo $form->field($model, 'match_event_type_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(MatchEventType::find()->all(), 'id', 'name'),
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Выберите тип события...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'minute')->textInput() ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'additional_minute')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?php
                $compositionFilter = Composition::find()->where(['match_id' => $model->match_id])->all();

                echo $form->field($model, 'composition_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($compositionFilter, 'id', 'name'),
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Выберите игрока...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
        <div class="col-sm-6">
            <?php
                echo $form->field($model, 'substitution_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map($compositionFilter, 'id', 'name'),
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Выберите игрока...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            ?>
        </div>
    </div>

    <?= $form->field($model, 'notes')->textarea(['rows' => 6]) ?>

    <?php if(!$model->isNewRecord) { ?>
        <?= $form->field($model, 'is_hidden')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>
    <?php } ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
