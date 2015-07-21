<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\VideoPost */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Видеозаписи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Просмотр', $model->getUrl(), ['class' => 'btn btn-success']) ?>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить видеозапись?',
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
                'value' => $model->content,
                'format' => 'html',
            ],
            [
                'label' => 'Изображение',
                'value' => empty($image->filename) ? null : Html::img($image->getFileUrl()),
                'format' => 'html',
            ],
            'created_at',
            'updated_at',
            [
                'attribute' => 'is_pin',
                'value' => $model->is_pin ? 'Да' : 'Нет',
            ],
            [
                'attribute' => 'is_public',
                'value' => $model->is_public ? 'Да' : 'Нет',
            ],
            [
                'label' => 'Количество комментариев',
                'value' => $model->getCommentsCount(), 
            ],
            'cached_tag_list',
        ],
    ]) ?>

</div>
