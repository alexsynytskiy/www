<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Coach */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Тренеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$photoIMG = '<img src="'.$photo->getFileUrl().'" style="height:300px; width: 200px;">';
?>
<div class="coach-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого тренера?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'label' => 'Фото',
                'value' => $photoIMG,
                'format' => 'html',
            ],
            'name',
            'birthday',
            'slug',
            'position',
            'notes:ntext',
            'player_carrer:ntext',
            'coach_carrer:ntext',
            [
                'attribute' => 'country.name',
                'label' => 'Страна',
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
