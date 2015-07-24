<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\Url;
use yii\web\JsExpression;
use common\models\Country;
/* @var $this yii\web\View */
/* @var $model common\models\Arbiter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="arbiter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?php
        $availableCountries = [];
        
        if(!$model->isNewRecord) {
            $country = Country::findOne($model->country_id);

            if(isset($country->id)) {
                $availableCountries = [$country->id => $country->name];
            }
        }

        echo $form->field($model, 'country_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['country/country-part-list']),
            'items' => $availableCountries,
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
