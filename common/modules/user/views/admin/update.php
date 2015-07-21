<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\modules\user\models\User $user
 * @var common\modules\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Изменить данные пользователя:') .' '. $user->getDisplayName();
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->getDisplayName(), 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = Yii::t('user', 'Изменить');
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'user' => $user,
        'avatar' => $avatar,
        'profile' => $profile,
    ]) ?>

</div>
