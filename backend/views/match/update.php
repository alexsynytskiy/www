<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Match */

$this->title = 'Изменить матч: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Матчи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="match-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', compact(
        'model', 
        'compositionForm',
        'homeComposition', 
        'guestComposition',
        'homeContractType',
        'guestContractType',
        'homeCompositionDataProvider',
        'guestCompositionDataProvider'
    )) ?>

</div>
