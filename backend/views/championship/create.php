<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Championship */

$this->title = 'Добавить турнир';
$this->params['breadcrumbs'][] = ['label' => 'Турниры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="championship-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
