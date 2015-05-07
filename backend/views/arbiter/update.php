<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Arbiter */

$this->title = 'Update Arbiter: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Arbiters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="arbiter-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
