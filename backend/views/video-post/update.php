<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VideoPost */

$this->title = 'Изменить видеозапись: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Видеозаписи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="video-post-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'image' => $image,
        'videoAsset' => $videoAsset,
        'tags' => $tags,
        'relation' => $relation,
        'matchModel' => $matchModel,
        'matchesList' => $matchesList,
    ]) ?>

</div>
