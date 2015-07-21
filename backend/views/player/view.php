<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Player */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Игроки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$avatar = $model->getAsset();
?>
<div class="player-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'firstname',
            'lastname',
            [
                'label' => 'Изображение',
                'value' => Html::img($avatar->getFileUrl()),
                'format' => 'html',
            ],
            'birthday',
            'slug',
            'height',
            'weight',
            [
                'label' => 'Амплуа',
                'attribute' => 'amplua.name',
            ],
            'more_ampluas',
            [
                'label' => 'Страна',
                'attribute' => 'country.name',
            ],
            'notes:html',
            'created_at',
            'updated_at',
            // 'image',
        ],
    ]) ?>

</div>
