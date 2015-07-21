<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Composition */

$this->title = 'Изменить данные о составе:';
$this->params['breadcrumbs'][] = ['label' => 'Составы команд матчей', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="composition-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
