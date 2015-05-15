<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Stadium */

$this->title = 'Create Stadium';
$this->params['breadcrumbs'][] = ['label' => 'Stadia', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stadium-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
