<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Claim */

$this->title = 'Изменить жалобу: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Жалобы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="claim-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
