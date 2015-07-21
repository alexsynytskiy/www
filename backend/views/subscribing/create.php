<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Subscribing */

$this->title = 'Добавить email в подписку';
$this->params['breadcrumbs'][] = ['label' => 'Подписка', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscribing-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
