<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Membership */

$this->title = 'Изменить игрока: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Состав клубов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="membership-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
