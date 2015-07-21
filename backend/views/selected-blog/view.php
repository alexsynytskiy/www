<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SelectedBlog */

$this->title = $model->post->title;
$this->params['breadcrumbs'][] = ['label' => 'Избранные блоги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="selected-blog-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить блог из списка избранных?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'post.title',
        ],
    ]) ?>

</div>
