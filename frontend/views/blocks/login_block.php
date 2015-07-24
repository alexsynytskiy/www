<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var app\models\User $user
 */

$this->title = Yii::t('user', 'Вход');
?>

<div class="login-box default-box">
    <div class="box-header">
        <div class="box-title">Вход</div>
    </div>
    <div class="box-content">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'default-form'],
        ]); ?>

            <?= $form->field($user, 'username', [
                'template' => "<div class=\"field field-email text-field\">{input}<div class=\"status-box\"></div></div><div class=\"error-msg\">{error}</div>",
            ])->textInput(['placeholder' => 'Email*']) ?>

            <?= $form->field($user, 'password', [
                'template' => "<div class=\"field field-pass text-field\">{input}<div class=\"status-box\"></div></div><div class=\"error-msg\">{error}</div>",
            ])->passwordInput(['placeholder' => 'Пароль*']) ?>

            <?= $form->field($user, 'rememberMe', [
                'template' => "<div class=\"field field-remember checkbox-field\">{input}</div>",
            ])->checkbox() ?>

            <div class="field field-submit field-submit-login">
                <?= Html::submitInput(Yii::t('user', 'Войти'), ['class' => '']) ?>
            </div>

            <div class="field field-link field-link-login">
                <?= Html::a(Yii::t("user", "Напомнить пароль"), ["/user/forgot"]) ?>
            </div>

            <div class="form-description">
                <span class="main-blue">*</span> - поля обязательны для заполнения
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>