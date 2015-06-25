<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Achievement */



$this->title = 'Добавить достижение';
if(isset($model->player)) {
    $this->params['breadcrumbs'][] = ['label' => $model->player->name, 'url' => ['/player/update', 'id' => $model->player->id]];
}
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="achievement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
