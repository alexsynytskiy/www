<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\markdown\Markdown;
use common\models\Asset;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['/post']];
$this->params['breadcrumbs'][] = $this->title;

$image = $model->getAsset(Asset::THUMBNAIL_NEWS);
?>
<div class="post-view">

    <h1><?= $this->title ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить запись?',
                'method' => 'post',
            ],
        ]) ?>
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
                'value' => Markdown::convert($model->content),
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
            'source_title',
            'source_url:url',
            'created_at',
            'updated_at',
            [
                'attribute' => 'is_public',
                'value' => $model->is_public ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'allow_comment',
                'value' => $model->allow_comment ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_top',
                'value' => $model->is_top ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_video',
                'value' => $model->is_video ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_cover',
                'value' => $model->is_cover ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_index',
                'value' => $model->is_index ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_yandex_rss',
                'value' => $model->is_yandex_rss ? 'Да' : 'Нет',
            ],
            'photo_id',
            'comments_count',
            'cached_tag_list',
        ],
    ]) ?>

</div>
