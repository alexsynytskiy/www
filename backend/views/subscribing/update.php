<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Subscribing */

$this->title = 'Изменить email: ' . $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Подписка', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->email, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="subscribing-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
