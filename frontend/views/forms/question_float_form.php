<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $question common\models\Question
 * @var $answers array of common\models\Question
**/

$inquirerMoreLink = (Yii::$app->controller->action->id == 'inquirers') ? false : true;

$adminLink = '';
if(Yii::$app->user->can('admin')) {
  $adminLink = '<a class="admin-view-link" target="_blank" href="/admin/question/'.$question->id.'"></a>';
} 
?>

<div class="inquirer default-box">
    <div class="box-header">
        <div class="box-title">Опрос <?= $adminLink ?></div>
        <?php if($inquirerMoreLink) { ?>
        <a href="<?= Url::to('/inquirers') ?>">
            <div class="box-link">Все опросы:<div class="icon-arrow"></div></div>
        </a>
        <?php } ?>
    </div>
    <div class="box-content">
        <div class="inquirer-theme"><?= $question->title ?></div>
        <?php $form = ActiveForm::begin([
            'id' => 'inquirer-float-form',
            'action' => Url::to('/question/vote-float'),
            'options' => [
                'class' => 'inquirer-form inquirer-float-form default-form',
            ],
        ]); ?>
            <?php foreach ($answers as $answer) { ?>
            <div class="inquirer-answer float">
                <label for="answer-<?= $answer->id ?>"><?= $answer->title ?></label>
                <div class="selectize-box">
                    <select id="answer-<?= $answer->id ?>" name="answer[<?= $answer->id ?>]">
                        <option value="4">  4.0</option>
                        <option value="4.5">4.5</option>
                        <option value="5">  5.0</option>
                        <option value="5.5">5.5</option>
                        <option value="6" selected="selected">6.0</option>
                        <option value="6.5">6.5</option>
                        <option value="7">  7.0</option>
                        <option value="7.5">7.5</option>
                        <option value="8">  8.0</option>
                    </select>
                </div>
            </div>
            <?php } ?>
            <?php if(isset(Yii::$app->user->id)) { ?>
            <div class="form-actions">
                <div class="field field-submit">
                    <input type="submit" id="inquirer-submit" name="inquirer-submit" value="Голосовать">
                </div>
                <div class="inquirer-date"><?= date('d.m.Y', strtotime($question->created_at)) ?></div>
            </div>
            <?php } else { ?>
            <div class="form-description">
                Для того чтобы проголосовать вы должны <a href="<?= Url::to('/user/login') ?>">авторизоваться</a>
            </div>
            <?php } ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>