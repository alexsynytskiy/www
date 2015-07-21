<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Vote */

$this->title = 'Голос ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Голосование', 'url' => ['/vote']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php // echo Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить голос?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'vote',
            'created_at',
            'voteable_type',
            'voteable_id',
            'user_id',
            'ip_address',
        ],
    ]) ?>

</div>
