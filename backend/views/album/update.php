<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $image common\models\Asset */
/* @var $tags array Array of common\models\Tag */

$this->title = 'Изменить альбом: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Альбомы', 'url' => ['/album']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="post-update">

    <h1><?= Html::decode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'images' => $images,
        'coverImage' => $coverImage,
        'tags' => $tags,
        'relation' => $relation,
        'matchModel' => $matchModel,
        'matchesList' => $matchesList,
    ]) ?>

</div>
