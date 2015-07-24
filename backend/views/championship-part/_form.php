<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\Url;
use yii\web\JsExpression;
use common\models\Championship;

/* @var $this yii\web\View */
/* @var $model common\models\ChampionshipPart */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="championship-part-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
    
    <?php
        $availableChampionships = [];
        
        if(!$model->isNewRecord) {
            $championship = Championship::findOne($model->championship_id);

            if(isset($championship->id)) {
                $availableChampionships = [$championship->id => $championship->name];
            }
        }

        echo $form->field($model, 'championship_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['championship/championship-part-list']),
            'items' => $availableChampionships,
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

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
