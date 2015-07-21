<?php

use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\modules\user\models\Profile $profile
 */

$this->title = 'Личный кабинет';
$userName = $profile->user->getDisplayName();
$avatar = $profile->user->getAsset();
$imageUrl = $avatar->getFileUrl();
$createTime = date('d.m.Y', strtotime($profile->user->create_time));
$loginTime = date('d.m.Y', strtotime($profile->user->login_time));
?>

<div class="profile-box">
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