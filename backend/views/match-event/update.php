<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MatchEvent */

$this->title = 'Изменить событие матча: ' . ' ' . $model->match->name;
$this->params['breadcrumbs'][] = ['label' => 'События матчей', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->match->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="match-event-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
