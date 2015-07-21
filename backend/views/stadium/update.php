<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Stadium */

$this->title = 'Изменить стадион: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Стадионы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="stadium-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
