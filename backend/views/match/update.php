<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Match */

$this->title = 'Изменить матч: ' . ' ' . $model->teamHome->name . ' - ' . $model->teamGuest->name;
$this->params['breadcrumbs'][] = ['label' => 'Матчи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->teamHome->name . ' - ' . $model->teamGuest->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="match-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
