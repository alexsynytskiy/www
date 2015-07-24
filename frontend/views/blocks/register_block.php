<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var common\modules\user\models\User $user
 * @var common\modules\user\models\User $profile
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
        <?php $form = ActiveForm::begin([
            'id' => 'register-form',
            'options' => [
                'class' => 'default-form',
                'enctype' => 'multipart/form-data',
            ],
            // 'enableAjaxValidation' => true,
        ]); ?>

        <?= $form->field($profile, 'full_name', [
                    'template' => '<div class="field field-username text-field">{input}<div class="status-box"></div></div><div class="error-msg">{error}</div>',
                ])->textInput(['placeholder' => 'Имя*']) ?>

        <?= $form->field($user, 'email', [
                    'template' => '<div class="field field-email text-field">{input}<div class="status-box"></div></div><div class="error-msg">{error}</div>',
                ])->textInput(['placeholder' => 'Email*']) ?>

        <?= $form->field($user, 'newPassword', [
                    'template' => '<div class="field field-pass1 text-field">{input}<div class="status-box"></div></div><div class="error-msg">{error}</div>',
                ])->passwordInput(['placeholder' => 'Пароль*']) ?>

        <?= $form->field($user, 'newPasswordConfirm', [
                    'template' => '<div class="field field-pass2 text-field">{input}<div class="status-box"></div></div><div class="error-msg">{error}</div>',
                ])->passwordInput(['placeholder' => 'Повторите пароль*']) ?>

        <?= $form->field($user, 'captcha', [
                    'template' => '{input}<div class="error-msg">{error}</div>',
                ])->widget(Captcha::className(), [
                'captchaAction' => \yii\helpers\Url::to('/site/captcha'),
                'template' => '<div class="captcha-image">{image}</div>
                    <div class="field field-captcha text-field">
                        {input}<div class="status-box"></div>
                    </div>',
                'options' => ['placeholder' => 'Введите код с картинки*'],
            ]) ?>

        <?= $form->field($user, 'avatar', [
                    'template' => '<div class="preview-image"></div><div class="error-msg">{error}</div>'.
                        '<div class="field field-avatar upload-button">'.
                        '<div class="field-label">Аватар</div>{input}</div>',
                ])->fileInput() ?>
                
        <?= $form->field($user, 'cropData')->hiddenInput(['id' => 'crop-data'])->label(false) ?>

        <div class="field field-submit field-submit-register">
            <?= Html::submitInput('Зарегистрировать', ['class' => 'btn-register']) ?>
        </div>
        <div class="form-description">
            <span class="main-blue">*</span> - поля обязательны для заполнения
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
