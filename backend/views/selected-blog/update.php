<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SelectedBlog */

$this->title = 'Изменить выбор редакции: ' . ' ' . $model->post->title;
$this->params['breadcrumbs'][] = ['label' => 'Выбранные редакцией блоги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->post->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="selected-blog-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
