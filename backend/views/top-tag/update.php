<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TopTag */

$this->title = 'Изменить топовый тег: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Топовые теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="top-tag-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
