<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BannedIP */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Заблокированные IP адреса', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banned-ip-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить заблокированный IP?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'start_ip_num_value',
            'end_ip_num_value',
            'is_active',
            'start_ip_num',
            'end_ip_num',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
