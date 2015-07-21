<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BannedIP */

$this->title = 'Изменить данные о блокировке: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Заблокированные IP адреса', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="banned-ip-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
