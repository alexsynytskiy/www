<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = 'Добавить запись';
$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['/post']];
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
