<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\League */

$this->title = 'Добавить лигу';
$this->params['breadcrumbs'][] = ['label' => 'Типы лиг', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="league-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
