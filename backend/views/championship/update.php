<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Championship */

$this->title = 'Update Championship: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Championships', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="championship-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
