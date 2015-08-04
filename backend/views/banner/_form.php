<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model common\models\Banner */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->widget(\vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'buttons' => ['html', 'formatting', 'bold', 'italic', 'underline', 'deleted',
                'unorderedlist', 'orderedlist', 'outdent', 'indent',
                'image', 'link', 'alignment'],
            'imageUpload' => \yii\helpers\Url::to(['/site/image-upload']),
            'plugins' => [
                'fullscreen',
                'table',
                'video',
                'fontcolor',
            ],
            'deniedTags' => ['style'],
            'replaceDivs' => false,
        ],
    ]); ?>
    
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'region')->dropDownList($model::dropdownRegions()) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'weight')->textInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
