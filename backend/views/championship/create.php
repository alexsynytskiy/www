<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Championship */

$this->title = 'Create Championship';
$this->params['breadcrumbs'][] = ['label' => 'Championships', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="championship-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
