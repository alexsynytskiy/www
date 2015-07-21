<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Vote */

$this->title = 'Изменить голос: '. $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Голосование', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="vote-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
