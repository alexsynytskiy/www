<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $image common\models\Asset */
/* @var $tags array Array of common\models\Tag */

$this->title = 'Изменить запись: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['/post']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="post-update">

    <h1><?= Html::decode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'image' => $image,
        'tags' => $tags,
        'relation' => $relation,
        'matchModel' => $matchModel,
        'matchesList' => $matchesList,
    ]) ?>

</div>
