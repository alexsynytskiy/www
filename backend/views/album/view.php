<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Asset;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Альбомы', 'url' => ['/album']];
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
                'confirm' => 'Вы уверены, что хотите удалить альбом?',
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
                'value' => $model->user ? Html::a($model->user->username ,['module/user/admin/view/'.$model->user_id]) : null,
                'format' => 'html',
            ],
            'title:html',
            'slug',
            [
                'attribute' => 'description',
                'value' => $model->description,
                'format' => 'html',
            ],
            // [
            //     'label' => 'Изображение',
            //     'value' => empty($image->filename) ? null : Html::img($image->getFileUrl()),
            //     'format' => 'html',
            // ],
            'created_at',
            'updated_at',
            [
                'attribute' => 'is_public',
                'value' => $model->is_public ? 'Да' : 'Нет',
            ],
            'cached_tag_list',
        ],
    ]) ?>
    
    <div class="panel panel-default">
        <div class="panel-heading"><strong>Изображения</strong></div>
        <div class="panel-body">
            <?php
            foreach($images as $image)
            {
                if(!empty($image->getFileUrl())) {
                    $img = Html::img($image->getFileUrl(), ['style' => 'height: 160px; margin: 0 10px 10px 0']);
                    echo Html::a($img, $image->getFileUrl(), ['target' => '_blank']);
                } else echo $image->id.' ';
            }
            ?>
        </div>
    </div>

</div>
