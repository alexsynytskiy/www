<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var common\modules\user\models\User $user
 * @var common\modules\user\models\Profile $profile
 */

$this->title = 'Hастройки';
$userName = $profile->user->getDisplayName();
$avatar = $profile->user->getAsset();
$imageUrl = $avatar->getFileUrl();
$createTime = date('d.m.Y', strtotime($profile->user->create_time));
$loginTime = date('d.m.Y', strtotime($profile->user->login_time));
?>
<div class="user-default-account">

    <?php if ($flash = Yii::$app->session->getFlash("Account-success")): ?>

        <div class="alert alert-success">
            <p><?= $flash ?></p>
        </div>

    <?php elseif ($flash = Yii::$app->session->getFlash("Resend-success")): ?>

        <div class="alert alert-success">
            <p><?= $flash ?></p>
        </div>

    <?php elseif ($flash = Yii::$app->session->getFlash("Cancel-success")): ?>

        <div class="alert alert-success">
            <p><?= $flash ?></p>
        </div>

    <?php endif; ?>

</div>


<div class="profile-box">
    <div class="top-part ">
        <img src="<?= $imageUrl ?>" class="photo">
        <div class="info-about-user">
            <div class="user-name">Автор: <span><?= $userName ?></span></div>
            <div class="user-date">Зарегистрирован: <span><?= $createTime ?></span></div>
            <div class="user-date">Последний визит: <span><?= $loginTime ?></span></div>
        </div>

        <a id="show-profile" class="edit-button show-profile" href="<?= Url::to(['/user/profile']) ?>">
            <div class="icon"></div>
        </a>

        <a id="new-post" class="edit-button new-post" href="<?= Url::to(['/post/add']) ?>">
            <div class="icon"></div>
        </a>
    </div>

        <?php $form = ActiveForm::begin([
            'id' => 'edit-profile-form',
            'options' => [
                'class' => 'default-form',
                'enctype' => 'multipart/form-data',
            ],
            'enableAjaxValidation' => true,
        ]); ?>
        <div class="top-desc">Изменить информацию и настройки</div>
        <div class="left-side">

            <?= $form->field($profile, 'full_name', [
                    'template' => '<div class="field field-username text-field">{input}<div class="status-box"></div></div><div class="error-msg">{error}</div>',
                ])->textInput(['placeholder' => 'Имя*']) ?>

            <?= $form->field($user, 'email', [
                    'template' => '<div class="field field-email text-field">{input}<div class="status-box"></div></div><div class="error-msg">{error}</div>',
                ])->textInput(['placeholder' => 'Email*']) ?>

            <?= $form->field($user, 'currentPassword', [
                    'template' => '<div class="field field-pass1 text-field">{input}<div class="status-box"></div></div><div class="error-msg">{error}</div>',
                ])->passwordInput(['placeholder' => 'Текущий пароль*']) ?>

            <?= $form->field($user, 'newPassword', [
                    'template' => '<div class="field field-pass2 text-field">{input}<div class="status-box"></div></div><div class="error-msg">{error}</div>',
                ])->passwordInput(['placeholder' => 'Новый пароль']) ?>

        </div>
        <div class="right-side">
            <?= $form->field($profile, 'description', [
                    'template' => '<div class="field field-about textarea-field">{input}</div><div class="error-msg">{error}</div>',
                ])->textarea(['placeholder' => 'Информация о себе']) ?>
        </div>

        <!-- <div class="preview-avatar preview-image"></div> -->

        <div class="form-actions">
        
            <?= $form->field($user, 'avatar', [
                    'template' => '<div class="preview-image"></div><div class="error-msg">{error}</div>'.
                        '<div class="field field-avatar-grey upload-button">'.
                        '<div class="field-label">Загрузить аватар</div>{input}</div>',
                ])->fileInput() ?>
                
            <?= $form->field($user, 'cropData')->hiddenInput(['id' => 'crop-data'])->label(false) ?>

            <?php if($user->isSubscribed()) { ?>
            <div class="field field-submit-grey field-submit-subscribe">
                <a class="button" href="<?= Url::to('/unsubscribe/'.$user->getUnsubscribeKey()) ?>">
                    Отписаться от новостей
                </a>
            </div>
            <?php } ?>

            <!-- <div class="replies-subscribe-box">
                <div class="input-box">
                    <input id="replies-subscribe-check" type="checkbox" name="replies-subscribe-check">
                </div>
                <label for="replies-subscribe-check" class="">Присылать на e-mail ответы на мои комментарии</label>
            </div> -->

            <div class="field field-submit field-submit-edit">
                <?= Html::submitInput("Изменить настройки") ?>
            </div>

        </div>
        <div class="clearfix"></div>
    <?php ActiveForm::end(); ?>
</div>