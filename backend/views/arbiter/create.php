<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Arbiter */

$this->title = 'Create Arbiter';
$this->params['breadcrumbs'][] = ['label' => 'Arbiters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="arbiter-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
