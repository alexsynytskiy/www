<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ChampionshipPart */

$this->title = 'Create Championship Part';
$this->params['breadcrumbs'][] = ['label' => 'Championship Parts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="championship-part-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
