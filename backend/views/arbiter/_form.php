<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\Url;
use yii\web\JsExpression;
use common\models\Country;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Arbiter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="arbiter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?php 
        echo $form->field($model, 'country_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Country::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
            'language' => 'ru',            
            'options' => ['placeholder' => 'Выберите страну...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
