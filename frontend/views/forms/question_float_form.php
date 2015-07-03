<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

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
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5" selected="selected">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
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