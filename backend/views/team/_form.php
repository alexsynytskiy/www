<?php

use common\models\Country;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Command */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="command-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?php 
    echo $form->field($model, 'info')->widget(\vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'imageUpload' => \yii\helpers\Url::to(['/site/image-upload']),
            'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted',
                'unorderedlist', 'orderedlist', 'outdent', 'indent',
                'image', 'link', 'alignment', 'horizontalrule'],
            'plugins' => [
                // 'clips',
                'fullscreen',
                'table',
                'video',
                'fontcolor',
            ]
        ]
    ]);
    ?>

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
