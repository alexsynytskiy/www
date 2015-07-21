<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Tournament */

$this->title = 'Добавить команду в турнир';
$this->params['breadcrumbs'][] = ['label' => 'Турнир', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tournament-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
