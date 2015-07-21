<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ChampionshipPart */

$this->title = 'Изменить этап турнира: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Этапы турнира', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="championship-part-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
