<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */

$this->title = 'Комментарий #'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Комментарии', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот комментарий?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'user.username',
                'label' => 'Автор',
                'value' => Html::a($model->user->username, ['module/user/admin/view/'.$model->user_id]),
                'format' => 'html',
            ],
            [
                'attribute' => 'content',
                'format' => 'html',
            ],
            'created_at',
            [
                'label' => 'Материал',
                'value' => $model->getCommentableLink(),
                'format' => 'html',
            ],
            [
                'attribute' => 'parent_id',
                'label' => 'Родитель',
                'value' => $model->parent_id ? Html::a('Перейти', ['comment/'.$model->parent_id]) : null,
                'format' => 'html',
            ],
        ],
    ]) ?>

</div>
