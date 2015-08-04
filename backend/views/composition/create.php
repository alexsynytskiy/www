<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use dosamigos\selectize\SelectizeDropDownList;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Amplua;

/* @var $this yii\web\View */
/* @var $model common\models\Composition */

$this->title = 'Добавить игрока в состав';
$this->params['breadcrumbs'][] = ['label' => 'Составы команд матчей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="composition-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        $form = ActiveForm::begin([
            'id' => 'team'.$model->command_id.'-player-add-form',
        ]);

        echo $form->field($contractModel, 'player_id')->widget(SelectizeDropDownList::classname(), [
            'loadUrl' => Url::to(['player/player-list']),        
            'options' => [
                'multiple' => false,
            ],
            'clientOptions' => [
                'valueField' => 'value',
                'labelField' => 'text',
                'persist' => false,
            ],
        ]);

        echo $form->field($contractModel, 'amplua_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(Amplua::find()->all(), 'id', 'name'),
            'language' => 'ru',
            'options' => ['placeholder' => 'Выберите амплуа...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);

        echo $form->field($contractModel, 'number')->textInput();

        echo $form->field($model, 'is_basis')
            ->widget(CheckboxX::classname(), [
                'pluginOptions' => ['threeState' => false], 
                'options' => ['id' => 'team'.$model->command_id.'-create-is_basis'],
            ]);
        echo $form->field($model, 'is_captain')
            ->widget(CheckboxX::classname(), [
                'pluginOptions' => ['threeState' => false], 
                'options' => ['id' => 'team'.$model->command_id.'-create-is_captain'],
            ]);

        echo Html::submitButton('Добавить', ['class' => 'btn btn-primary']);

        ActiveForm::end();
    ?>

</div>
