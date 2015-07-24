<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Tournament */

$this->title = 'Изменить команду: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Турнир', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="tournament-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
