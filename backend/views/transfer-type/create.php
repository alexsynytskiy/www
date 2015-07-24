<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TransferType */

$this->title = 'Добавить тип транфера';
$this->params['breadcrumbs'][] = ['label' => 'Типы трансферов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
