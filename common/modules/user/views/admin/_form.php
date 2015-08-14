<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use kartik\file\FileInput;

$role = Yii::$app->getModule("user")->model("Role");

/**
 * @var yii\web\View $this
 * @var common\modules\user\models\User $user
 * @var common\modules\user\models\Profile $profile
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['options' => [
        'autocomplete' => 'off',
        'enctype' => 'multipart/form-data',
    ]]); ?>

    <?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($user, 'username')->textInput() ?>

    <?= $form->field($user, 'newPassword')->passwordInput() ?>

    <?= $form->field($profile, 'full_name')->textInput(); ?>

    <?php
    $pluginOptions = [
        'showUpload' => false,
        'showRemove' => false,
        'overwriteInitial' => true,
        'browseLabel' => "Обзор...",
        'allowedFileExtensions' => ['jpg', 'gif', 'png'],
    ];
    if (!$user->isNewRecord && $avatar->getFileUrl())
    {
        $pluginOptions['initialPreview'] = [
            Html::img($avatar->getFileUrl()),
        ];
    }
    echo $form->field($user, 'avatar')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => false,
            'class' => 'jcrop',
        ],
        'pluginOptions' => $pluginOptions,
    ]);
    echo $form->field($user, 'cropData')->hiddenInput(['id' => 'crop-data'])->label(false);
    ?>

    <?= $form->field($user, 'role_id')->dropDownList($role::dropdown()); ?>

    <?php $disabled = $user->status == $user::STATUS_BANNED_FOREVER ? 'disabled' : false; ?>
    <?php $readOnly = $user->status == $user::STATUS_BANNED_FOREVER ? true : false; ?>
    <?= $form->field($user, 'status')->dropDownList($user::statusDropdown(),['readonly' => $readOnly, 'disabled' => $disabled]); ?>

    <?php // use checkbox for ban_time ?>
    <?php // convert `ban_time` to int so that the checkbox gets set properly ?>
    <?php $user->ban_time = $user->ban_time ? 1 : 0 ?>

    <?= $form->field($user, 'ban_time')->widget(CheckboxX::classname(), [
        'pluginOptions' => ['threeState' => false],
        'readonly' => $readOnly,
    ])->label('Заблокирован') ?>

    <?= $form->field($user, 'ban_reason'); ?>

    <div class="form-group">
        <?= Html::submitButton($user->isNewRecord ? Yii::t('user', 'Создать') : Yii::t('user', 'Изменить'), ['class' => $user->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
