<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Contract */

$this->title = 'Добавить игрока в команду';
$this->params['breadcrumbs'][] = ['label' => 'Игроки команд', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contract-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
