<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TopTag */

$this->title = 'Добавить тег в топ';
$this->params['breadcrumbs'][] = ['label' => 'Топовые теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="top-tag-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
