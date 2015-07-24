<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var common\modules\user\models\forms\ForgotForm $model
 */

$this->title = Yii::t('user', 'Forgot password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="register-box default-box">
    <div class="box-header">
        <div class="box-title">Напомнить пароль</div>
    </div>
    <div class="box-content">
        <?php $form = ActiveForm::begin([
            'id' => 'forgot-form',
            'options' => ['class' => 'default-form'],
        ]); ?>

        <?= $form->field($model, 'email', [
            'template' => "<div class=\"field field-email text-field\">{input}<div class=\"status-box\"></div></div><div class=\"error-msg\">{error}</div>",
        ])->textInput(['placeholder' => 'Email*']) ?>

        <div class="field field-submit field-submit-send">
            <?= Html::submitInput('Отправить', ['class' => '']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>
