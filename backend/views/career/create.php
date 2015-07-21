<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Career */

$this->title = 'Create Career';
$this->params['breadcrumbs'][] = ['label' => 'Careers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="career-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
