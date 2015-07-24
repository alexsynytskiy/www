<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = 'Добавить альбом';
$this->params['breadcrumbs'][] = ['label' => 'Альбомы', 'url' => ['/album']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'relation' => $relation,
        'matchModel' => $matchModel,
        'matchesList' => $matchesList,
    ]) ?>

</div>
