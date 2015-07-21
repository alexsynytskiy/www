<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MainInfo */

$this->title = 'Изменить информацию о команде: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Информация о команде', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="main-info-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
