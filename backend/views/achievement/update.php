<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Achievement */

$this->title = 'Изменить достижение: ' . $model->name;
if(isset($model->player)) {
    $this->params['breadcrumbs'][] = ['label' => $model->player->name, 'url' => ['/player/update', 'id' => $model->player->id]];
}
$this->params['breadcrumbs'][] = 'Изменить достижение';
?>
<div class="achievement-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
