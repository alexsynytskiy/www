<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Amplua;

/* @var $this yii\web\View */
/* @var $model common\models\Composition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="composition-form">

    <?php $form = ActiveForm::begin([
        'id' => 'composition-form',
    ]); ?>

    <?= $form->field($model, 'number')->textInput(['id' => 'team'.$model->command_id.'-number']) ?>
    
    <?= $form->field($model, 'amplua_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Amplua::find()->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => [
                'placeholder' => 'Выберите амплуа...',
                'id' => 'team'.$model->command_id.'-amplua_id'
            ],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>

    <?= $form->field($model, 'is_basis')->widget(CheckboxX::classname(), [
            'pluginOptions' => ['threeState' => false],
            'options' => [
                'id' => 'team'.$model->command_id.'-is_basis'
            ],
        ]) ?>

    <?= $form->field($model, 'is_captain')->widget(CheckboxX::classname(), [
            'pluginOptions' => ['threeState' => false],
            'options' => [
                'id' => 'team'.$model->command_id.'-is_captain'
            ],
        ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
