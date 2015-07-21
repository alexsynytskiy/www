<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\selectize\SelectizeDropDownList;
use kartik\checkbox\CheckboxX;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use common\models\Season;
use common\models\Team;
use common\models\Coach;

/* @var $this yii\web\View */
/* @var $model common\models\TeamCoach */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="team-coach-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php 
        echo $form->field($model, 'team_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Team::find()->where(['id' => Team::getTeamsConstants()])->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите команду...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?php 
        echo $form->field($model, 'season_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Season::find()->where(['window' => Season::WINDOW_WINTER])->orderBy(['name' => SORT_DESC])->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите сезон...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?php 
        echo $form->field($model, 'coach_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Coach::find()->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите тренера...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?php
        // $availableCoaches = [];
        // $coach = $model->coach;
        // if(isset($coach->id)) {
        //     $availableCoaches = [$coach->id => $coach->name];
        // }

        // echo $form->field($model, 'coach_id')->widget(SelectizeDropDownList::classname(), [
        //     'loadUrl' => Url::to(['coach/coach-list']),        
        //     'items' => $availableCoaches,
        //     'options' => [
        //         'multiple' => false,
        //         'placeholder' => 'Выберите тренера...',
        //     ],
        //     'clientOptions' => [
        //         'valueField' => 'value',
        //         'labelField' => 'text',
        //         'persist' => false,
        //     ],
        // ]);
    ?>

    <?= $form->field($model, 'is_main')->widget(CheckboxX::classname(), [
            'pluginOptions' => ['threeState' => false]
        ]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
