<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var amnah\yii2\user\models\User $user
 * @var amnah\yii2\user\models\User $profile
 * @var string $userDisplayName
 */

$this->title = Yii::t('user', 'Register');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="register-box default-box">
    <div class="box-header">
        <div class="box-title">Регистрация нового пользователя</div>
    </div>
    <div class="box-content">

    <?php if ($flash = Yii::$app->session->getFlash("Register-success")): ?>

        <div class="alert alert-success">
            <?= $flash ?>
            <div class="alert-desc">Этот блок закроется через <span class="sec">5</span> секунд</div>
        </div>

    <?php else: ?>

        <?php $form = ActiveForm::begin([
            'id' => 'register-form',
            'options' => ['class' => 'default-form'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
            'enableAjaxValidation' => true,
        ]); ?>

        <?= $form->field($profile, 'full_name', [
                    'template' => '<div class="field field-username text-field">{input}<div class="status-box"></div></div>',
                ])->textInput(['placeholder' => 'Имя*']) ?>

        <?= $form->field($user, 'email', [
                    'template' => '<div class="field field-email text-field">{input}<div class="status-box"></div></div>',
                ])->textInput(['placeholder' => 'Email*']) ?>

        <?= $form->field($user, 'newPassword', [
                    'template' => '<div class="field field-pass1 text-field">{input}<div class="status-box"></div></div>',
                ])->passwordInput(['placeholder' => 'Пароль*']) ?>

        <?= $form->field($user, 'newPasswordConfirm', [
                    'template' => '<div class="field field-pass2 text-field">{input}<div class="status-box"></div></div>',
                ])->passwordInput(['placeholder' => 'Повторите пароль*']) ?>

        <div class="field field-avatar upload-button">
            <div class="field-label">Аватар 80х80</div>
            <?= Html::activeFileInput($user, 'avatar') ?>
        </div>
        <div class="field field-submit field-submit-register">
            <?= Html::submitInput('Зарегистрировать', ['class' => 'btn-register']) ?>
        </div>
        <div class="form-description">
            <span class="main-blue">*</span> - поля обязательны для заполнения
        </div>

        <?php ActiveForm::end(); ?>

    <?php endif; ?>

    </div>
</div>
