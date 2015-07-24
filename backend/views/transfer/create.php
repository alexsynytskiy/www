<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Transfer */

$this->title = 'Добавить трансфер';
$this->params['breadcrumbs'][] = ['label' => 'Трансферы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
