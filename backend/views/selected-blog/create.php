<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\SelectedBlog */

$this->title = 'Добавить избранный блог';
$this->params['breadcrumbs'][] = ['label' => 'Избранные блоги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="selected-blog-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
