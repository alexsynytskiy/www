<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\VideoPost */

$this->title = 'Добавить видеозапись';
$this->params['breadcrumbs'][] = ['label' => 'Видеозаписи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'relation' => $relation,
        'matchModel' => $matchModel,
        'matchesList' => $matchesList,
    ]) ?>

</div>
