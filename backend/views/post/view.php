<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Asset;
use common\models\Vote;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['/post']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <h1><?= $this->title ?></h1>

    <p>
        <?= Html::a('Просмотр', $model->getUrl(), ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Добавить запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Автор',
                'attribute' => 'user.username',
                'value' => Html::a($model->user->username,['module/user/admin/view/'.$model->user_id]),
                'format' => 'html',
            ],
            'title:html',
            'slug',
            [
                'attribute' => 'content',
                'value' => $model->content,
                'format' => 'html',
            ],
            [
                'attribute' => 'content_category_id',
                'value' => $model->getCategory(),
            ],
            [
                'label' => 'Изображение',
                'value' => empty($image->filename) ? null : Html::img($image->getFileUrl()),
                'format' => 'html',
            ],
            'source_title:html',
            'source_url:url',
            'created_at',
            'updated_at',
            [
                'attribute' => 'is_index',
                'value' => $model->is_index ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_top',
                'value' => $model->is_top ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_pin',
                'value' => $model->is_pin ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'with_video',
                'value' => $model->with_video ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'with_photo',
                'value' => $model->with_photo ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_public',
                'value' => $model->is_public ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'allow_comment',
                'value' => $model->allow_comment ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_yandex_rss',
                'value' => $model->is_yandex_rss ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_vk_rss',
                'value' => $model->is_vk_rss ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_fb_rss',
                'value' => $model->is_fb_rss ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_tw_rss',
                'value' => $model->is_tw_rss ? 'Да' : 'Нет',
            ],
            [
                'label' => 'Количество комментариев',
                'value' => $model->getCommentsCount(), 
            ],
            'cached_tag_list',
            [
                'label' => 'Рейтинг',
                'value' => '<span class="label label-success">'.
                Vote::getVotes($model->id,Vote::VOTEABLE_POST,1).
                '</span> <span class="label label-danger">'.
                Vote::getVotes($model->id,Vote::VOTEABLE_POST,0).
                '</span>',
                'format' => 'html',
            ],
        ],
    ]) ?>

</div>
