<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $question common\models\Question
 * @var $answers array of common\models\Question
**/

$inquirerMoreLink = (Yii::$app->controller->action->id == 'inquirers') ? false : true;
?>

<div class="inquirer default-box">
    <div class="box-header">
        <div class="box-title">Опрос</div>
        <?php if($inquirerMoreLink) { ?>
        <a href="<?= Url::to('/inquirers') ?>"><div class="box-link">Все опросы:</div></a>
        <?php } ?>
    </div>
    <div class="box-content">
        <div class="inquirer-theme"><?= $question->title ?></div>
        <form id="inquirer-form" action="<?= Url::to('/admin/question/vote') ?>" class="inquirer-form default-form">
            <?php foreach ($answers as $answer) { ?>
            <div class="inquirer-answer">
                <div class="input-box">
                    <input id="answer-<?= $answer->id ?>" type="radio" name="answer" value="<?= $answer->id ?>">
                </div>
                <label for="answer-<?= $answer->id ?>"><?= $answer->title ?></label>
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
                Для того чтобы проголосовать вы должны <a href="/?q=login">авторизоваться</a>
            </div>
            <?php } ?>
        </form>
    </div>
</div>