<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\League */

$this->title = 'Изменить лигу: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы лиг', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="league-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
