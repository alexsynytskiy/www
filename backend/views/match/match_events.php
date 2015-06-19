<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Match */

$this->title = 'События матча: '. $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Матчи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
?>
<div class="match-events-container">
    <?= $this->render('@backend/views/match-event/index', [
        'dataProvider' => $matchEventDataProvider, 
        'searchModel' => $matchEventModelSearch, 
        'eventFilter' => $eventFilter,
    ]) ?>

    <?= $this->render('@backend/views/match-event/create', [
        'model' => $matchEventModel, 
    ]) ?>
</div>