<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MatchEventType */

$this->title = 'Добавить событие матча';
$this->params['breadcrumbs'][] = ['label' => 'События матча', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="match-event-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
