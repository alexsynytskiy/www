<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BannedIP */

$this->title = 'Добавить IP адрес';
$this->params['breadcrumbs'][] = ['label' => 'Заблокированные IP адреса', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banned-ip-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
