<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Url;

$user = Yii::$app->getModule("user")->model("User");
$role = Yii::$app->getModule("user")->model("Role");

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var common\modules\user\models\search\UserSearch $searchModel
 * @var common\modules\user\models\User $user
 * @var common\modules\user\models\Role $role
 */

$this->title = Yii::t('user', 'Пользователи');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
        <?php // echo Html::a('Удалить неподтвержденных', ['delete-pending-users'], ['class' => 'btn btn-warning']); ?>
        <?php
            if(count(Yii::$app->getRequest()->getQueryParams()) > 0) {
                echo Html::a('Сброс', ['/user/admin'], ['class' => 'btn btn-primary']);
            } 
        ?>
        <span class="pull-right">Мой IP: <?= Yii::$app->getRequest()->getUserIP() ?></span>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            // [
            //     'attribute' => 'id',
            //     'options' => ['width' => '90'],
            // ],
            // [
            //     'attribute' => 'role_id',
            //     'label' => Yii::t('user', 'Role'),
            //     'filter' => $role::dropdown(),
            //     'value' => function($model, $index, $dataColumn) use ($role) {
            //         $roleDropdown = $role::dropdown();
            //         return isset($roleDropdown[$model->role_id]) ? $roleDropdown[$model->role_id] : null;
            //     },
            //     'options' => ['width' => '130'],
            // ],
            [
                'attribute' => 'status',
                'label' => Yii::t('user', 'Status'),
                'filter' => $user::statusDropdown(),
                'value' => function($model, $index, $dataColumn) use ($user) {
                    $statusDropdown = $user::statusDropdown();
                    return $statusDropdown[$model->status];
                },
                'options' => ['width' => '160']
            ],
            'email:email',
            'profile.full_name',
            [
                'attribute' => 'create_time',
                'value' => function($model){
                    return date('d.m.Y H:i', strtotime($model->create_time));
                },
                'format' => 'text',
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'create_time',
                    'removeButton' => false,
                    'type' => DatePicker::TYPE_INPUT,
                    'language' => 'ru-RU',
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'autoclose' => true,
                    ]
                ]),
                'options' => ['width' => '140'],
            ],
            // 'new_email:email',
            // 'username',
            // 'password',
            // 'auth_key',
            // 'api_key',
            'login_ip',
            // 'login_time',
            'create_ip',
            // 'create_time',
            // 'update_time',
            // 'ban_time',
            // 'ban_reason',
            [
                'label' => '',
                'value' => function ($model) {
                    $url = Url::to(['comments', 'id' => $model->id]);
                    $url .= '?sort=-created_at';
                    return Html::a('<span class="glyphicon glyphicon-envelope"></span>', $url, [
                        'title' => 'Комментарии',
                    ]);
                },
                'format' => 'html',
                'options' => ['width' => '25'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '70'],
            ],
        ],
    ]); ?>

</div>
