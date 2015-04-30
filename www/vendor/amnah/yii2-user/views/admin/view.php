<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/**
 * @var yii\web\View $this
 * @var amnah\yii2\user\models\User $user
 */

$this->title = $user->getDisplayName();
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Пользователи'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$avatar = $user->getAsset();
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('user', 'Изменить'), ['update', 'id' => $user->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('user', 'Удалить'), ['delete', 'id' => $user->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('user', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $user,
        'attributes' => [
            'id',
            'profile.full_name',
            'username',
            'email:email',
            [
                'label' => 'Аватар',
                'value' => !empty($avatar->filename) ? Html::img($avatar->getFileUrl()) : null,
                'format' => 'html',
            ],
            'role.name',
            [
                'attribute' => 'status',
                'value' => $user->getStatus(),
            ],
            'profile.description',
            [
                'attribute' => 'password',
                'label' => 'Хеш пароля',
            ],
            'auth_key',
            'api_key',
            'login_ip',
            'login_time',
            'create_ip',
            'create_time',
            'update_time',
            'ban_time',
            'ban_reason',
        ],
    ]) ?>

</div>
