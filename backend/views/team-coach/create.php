<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TeamCoach */

$this->title = 'Добавить тренера в команду';
$this->params['breadcrumbs'][] = ['label' => 'Тренерский состав', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="team-coach-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
