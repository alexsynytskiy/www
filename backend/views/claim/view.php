<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Claim */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Жалобы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="claim-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить жалобу?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php 
        if(!isset($model->comment)) {
            $commentContent = 'Удален';
        }
        else {
            $link = Html::a('#'.$model->comment_id, ['/comment/view', 'id' => $model->comment_id]);
            $commentContent = $link.' '.$model->comment->content;
        }
        $username = isset($model->user) ? $model->user->username : '-';
        $userLink = Html::a($username, ['/user/admin/view', 'id' => $model->user_id]);
        $username = isset($model->commentAuthor) ? $model->commentAuthor->username : '-';
        $authorLink = Html::a($username, ['/user/admin/view', 'id' => $model->user_id]);
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Пользователь',
                'value' => $userLink,
                'format' => 'html',
            ],
            [
                'label' => 'Автор',
                'value' => $authorLink,
                'format' => 'html',
            ],
            [
                'attribute' => 'comment_id',
                'value' => $commentContent,
                'format' => 'html',
            ],
            'message',
            [
                'attribute' => 'created_at',
                'value' => date('d.m.Y H:i', strtotime($model->created_at)),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('d.m.Y H:i', strtotime($model->updated_at)),
            ],
        ],
    ]) ?>

</div>
