<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\selectize\SelectizeDropDownList;

/* @var $this yii\web\View */
/* @var $model common\models\TopTag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="top-tag-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    $availableTag = [];
    if(isset($model->tag))
    {
        $availableTag[$model->tag->id] = $model->tag->name;
    }

    echo $form->field($model, 'tag_id')->widget(SelectizeDropDownList::classname(), [
        'loadUrl' => '/admin/tag/tag-list',
        'items' => $availableTag,
        'options' => [
            'multiple' => false,
        ],
        'clientOptions' => [
            'delimiter' => ',',
            'persist' => false,
            'createOnBlur' => false,
            'maxItems' => 1,
            // 'create' => new JsExpression('function(input) { return { value: "{new}" + input, text: input } }'),
        ],
    ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
