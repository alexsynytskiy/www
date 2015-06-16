<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Contract */

$this->title = 'Изменить игрока: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Игроки команд', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="contract-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
