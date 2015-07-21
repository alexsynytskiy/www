<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var common\modules\user\models\Profile $profile
 */

$this->title = 'Личный кабинет';
$userName = $profile->user->getDisplayName();
$avatar = $profile->user->getAsset();
$imageUrl = $avatar->getFileUrl();
$createTime = date('d.m.Y', strtotime($profile->user->create_time));
$loginTime = date('d.m.Y', strtotime($profile->user->login_time));
?>

<div class="cabinet-posts">
    <div class="top-part ">
        <img src="<?= $imageUrl ?>" class="photo">
        <div class="info-about-user">
            <div class="user-name">Автор: <span><?= $userName ?></span></div>
            <div class="user-date">Зарегистрирован: <span><?= $createTime ?></span></div>
            <div class="user-date">Последний визит: <span><?= $loginTime ?></span></div>
        </div>

        <a id="edit-profile" class="edit-button edit-profile" href="<?= Url::to(['/user/edit']) ?>">
            <div class="icon"></div>
        </a>

        <a id="new-post" class="edit-button new-post" href="<?= Url::to(['/post/add']) ?>">
            <div class="icon"></div>
        </a>
    </div>
   
    <?php if(empty($profile->description)) { ?>
    <div class="empty-history">
        Добавьте дополнительную информацию о Вас в настройках профиля
    </div>
    <?php } else { ?>
    <div class="description">
        <?= $profile->description ?>
    </div>
    <?php } ?>
</div>




<!-- <div class="user-default-profile">

    <?php if ($flash = Yii::$app->session->getFlash("Profile-success")): ?>

        <div class="alert alert-success">
            <p><?= $flash ?></p>
        </div>

    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'id' => 'profile-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
        'enableAjaxValidation' => true,
    ]); ?>

    <?= $form->field($profile, 'full_name')->textInput(['maxlength' => 200]) ?>

    <?= $form->field($profile, 'description')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div> -->