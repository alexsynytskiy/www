<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TeamCoach */

$this->title = 'Изменить тренера в команде: ' . $model->team->name;
$this->params['breadcrumbs'][] = ['label' => 'Тренерский состав', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->coach->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="team-coach-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
