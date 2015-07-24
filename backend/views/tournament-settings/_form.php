<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Season;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\TournamentSettings */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tournament-settings-form">

    <?php $form = ActiveForm::begin(); ?>

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

    <?= $form->field($model, 'scored_missed_weight')->textInput() ?>

    <?= $form->field($model, 'goal_scored_weight')->textInput() ?>

    <?= $form->field($model, 'goal_missed_weight')->textInput() ?>

    <?= $form->field($model, 'win_weight')->textInput() ?>

    <?= $form->field($model, 'draw_weight')->textInput() ?>

    <?= $form->field($model, 'defeat_weight')->textInput() ?>

    <?= $form->field($model, 'cl_positions')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'el_positions')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reduction_positions')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
