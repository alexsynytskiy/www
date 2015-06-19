<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MatchEvent */

$this->title = 'Update Match Event: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Match Events', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="match-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
