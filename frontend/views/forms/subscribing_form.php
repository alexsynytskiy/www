<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model common\models\Subscribing
**/
?>
<div class="subscribe">
    <div class="title">
        Подписка на новости
    </div>
    <div class="icon"></div>
    <div class="subscribe-form">
        <?php $form = ActiveForm::begin(); ?>
            
            <?= $form->field($model, 'email')
                    ->textInput([
                        'placeholder' => 'Ваш e-mail', 
                        'class' => 'subscribe-textarea'
                    ])->label(false) ?>
            
            <?= Html::submitInput('Подписаться', ['class' => 'subscribe-button']) ?>

        <?php ActiveForm::end(); ?>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>