<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MatchEventType */

$this->title = 'Изменить событие матча: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'События матча', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="match-event-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'icon' => $icon,
    ]) ?>

</div>
