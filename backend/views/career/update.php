<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Career */

$this->title = 'Изменить карьеру игрока: ' . ' ' . $model->player->name;
$this->params['breadcrumbs'][] = ['label' => 'Карьеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->player->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="career-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
