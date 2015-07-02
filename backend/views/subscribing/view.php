<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Subscribing */

$this->title = $model->email;
$this->params['breadcrumbs'][] = ['label' => 'Подписка', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscribing-view">

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить email из подписки?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'email:email',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
