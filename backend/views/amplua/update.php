<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Amplua */

$this->title = 'Изменить амплуа: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Амплуа', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="amplua-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
