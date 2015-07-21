<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Coach */

$this->title = 'Изменить тренера: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Тренеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="coach-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'photo' => $photo,
    ]) ?>

</div>
