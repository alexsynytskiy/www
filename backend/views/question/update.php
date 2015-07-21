<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Question */

$this->title = 'Изменить опрос ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="question-update">

    <?= $this->render('_form', [
        'model' => $model,
        'answerForm' => $answerForm,
        'answersDataProvider' => $answersDataProvider,
    ]) ?>

</div>
