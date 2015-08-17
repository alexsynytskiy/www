<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model common\models\Match */

$this->title = 'Изменить статистику матча: '. $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Матчи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить статистику';
?>

<div class="match-form">

    <?php $form = ActiveForm::begin([
        'action' => "/admin/match/stat-update?id=".$model->id,
    ]);
    ?>

    <div class="row">
        <div class="col-sm-6">
            <h1><?= $model->teamHome->name ?></h1>
        </div>
        <div class="col-sm-6">
            <h1><?= $model->teamGuest->name ?></h1>
        </div>
    </div>

    <?= $this->render('stat_form', compact('model', 'form')) ?>

    <?= $form->field($model, 'is_finished')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>

    <div class="form-group">
        <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
