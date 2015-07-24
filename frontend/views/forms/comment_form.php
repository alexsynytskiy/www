<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\widgets\Pjax;

use common\modules\user\models\User;

/**
 * @var $this yii\web\View
 * @var $comments array Array of common\models\Comment
 * @var $commentForm common\models\Comment
**/

$user = User::findOne(Yii::$app->user->id);
$avatar = $user->getAsset();

?>

<?php $form = ActiveForm::begin([
    'id' => 'comment-form',
    'options' => ['class' => 'default-form'],
    'action' => Url::to('/site/comment-add'),
]); ?>

<div class="user-photo">
    <a href="<?= Url::to(['user/profile']) ?>"><img src="<?= $avatar->getFileUrl() ?>"></a>
</div>
<div class="field textarea-field comment-field">
    <?= $form->field($commentForm, 'content')->textArea([
            'placeholder' => 'Введите ваше сообщение',
            'class' => 'autosize',
        ])->label(false) ?>
</div>

<?= $form->field($commentForm, 'commentable_id')->hiddenInput(['value' => $commentForm->commentable_id])->label(false) ?>
<?= $form->field($commentForm, 'commentable_type')->hiddenInput(['value' => $commentForm->commentable_type])->label(false) ?>
<?= $form->field($commentForm, 'parent_id')->hiddenInput()->label(false) ?>

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
