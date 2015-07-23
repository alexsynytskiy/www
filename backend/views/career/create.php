<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Career */

$this->title = 'Создать карьеру игрока';
$this->params['breadcrumbs'][] = ['label' => 'Карьеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="career-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
