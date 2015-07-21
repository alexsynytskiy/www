<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Vote */

$this->title = 'Создать голос';
$this->params['breadcrumbs'][] = ['label' => 'Голосование', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
