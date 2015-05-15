<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use amnah\yii2\user\models\User;

/**
 * @var $this yii\web\View
 * @var $comments array Array of common\models\Comment
 * @var $commentModel common\models\Comment
**/

$user = User::findOne(Yii::$app->user->id);
$avatar = $user->getAsset();

?>

<?php $form = ActiveForm::begin([
    'id' => 'comment-form',
    'options' => ['class' => 'default-form'],
    'action' => Url::to(['site/comment-add']),
    // 'enableClientScript' => false,
    // 'enableAjaxValidation' => false,
]); ?>

<div class="user-photo">
    <a href="<?= Url::to(['user/profile']) ?>"><img src="<?= $avatar->getFileUrl() ?>"></a>
</div>
<div class="field textarea-field comment-field">
    <?= $form->field($commentModel, 'content')->textArea()->label(false) ?>
</div>

<?= $form->field($commentModel, 'commentable_id')->hiddenInput(['value' => $commentModel->commentable_id])->label(false) ?>
<?= $form->field($commentModel, 'commentable_type')->hiddenInput(['value' => $commentModel->commentable_type])->label(false) ?>
<?= $form->field($commentModel, 'parent_id')->hiddenInput()->label(false) ?>

<div class="comment-bottom">
    <div class="field field-submit">
        <?= Html::submitInput('Добавить комментарий') ?>
    </div>
    <div class="reply-data">
        Ответ для <span class="user">Пользователь</span>
        <a href="javascript:void(0)" class="button-cancel" title="Отменить"></a>
    </div>
</div>


<?php ActiveForm::end(); ?>

