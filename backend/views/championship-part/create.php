<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ChampionshipPart */

$this->title = 'Добавить этап турнира';
$this->params['breadcrumbs'][] = ['label' => 'Этапы турнира', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="championship-part-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
