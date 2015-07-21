<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Forward */

$this->title = 'Добавить бомбардира';
$this->params['breadcrumbs'][] = ['label' => 'Бомбардиры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forward-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
