<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use common\modules\user\models\User;

/**
 * @var $this yii\web\View
 * @var $comment common\models\Comment
 * @var $claim common\models\Claim
**/

$user = User::findOne(Yii::$app->user->id);
$avatar = $user->getAsset();
?>

<div class="comments-block">
    <div class="header">
        <div class="title">Комментарий</div>
        <div class="help"></div>
    </div>
    <?php 
        \common\models\Comment::outCommentsTree([[$comment]], 0, [
            'showReplyButton' => false,
        ]); 
    ?>
</div>

<?php if(isset($claim->id)) { ?>
    <div class="claim-box">
        <div class="header">
            <div class="title">Ваша жалоба</div>
        </div>
        <div class="claim-message"><?= $claim->message ?></div>
    </div>
<?php } else { ?>
    <div class="complain-form-box">
        <?php $form = ActiveForm::begin([
                'options' => ['class' => 'default-form complain-form'],
            ]); ?>
        
            <div class="user-photo">
                <a href="<?= Url::to(['user/profile']) ?>"><img src="<?= $avatar->getFileUrl() ?>"></a>
            </div>
            <div class="field textarea-field comment-field">
                <?= $form->field($claim, 'message')->textArea([
                        'maxlength' => 255, 
                        'placeholder' => 'Введите ваше сообщение', 
                        'class' => 'autosize',
                    ])->label(false) ?>
            </div>

            <div class="field field-submit">
                <?= Html::submitInput('Пожаловаться') ?>
            </div>
        
        <?php ActiveForm::end(); ?>
    </div>
<?php } ?>
