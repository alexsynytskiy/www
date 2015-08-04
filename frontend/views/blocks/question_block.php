<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $question common\models\Question
 * @var $answers array of common\models\Question
**/

$allVotes = 0;
foreach ($answers as $answer) { 
    $allVotes += $answer->voutes;
}
$inquirerMoreLink = (Yii::$app->controller->action->id == 'inquirers') ? false : true;

$adminLink = '';
if(Yii::$app->user->can('admin')) {
  $adminLink = '<a class="admin-view-link" target="_blank" href="/admin/question/'.$question->id.'"></a>';
} 

?>
<?php if($allVotes > 0) { ?>
<div class="inquirer inquirer-result default-box">
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
      
      <?php 
      foreach ($answers as $answer) { 
          $percent = round($answer->voutes/$allVotes * 100, 1);
          ?>
          <div class="answer-label"><?= $answer->title ?></div>
          <div class="answer-stat">
              <div class="stat-bar" style="width: <?= $percent ?>%">
                  <div class="stat-value"><?= $answer->voutes ?>(<?= $percent ?>%)</div>
              </div>
          </div>
      <?php } ?>

      <div class="inquirer-description">
        <div class="vote-count">Всего голосов <span><?= $allVotes ?></span></div>
        <div class="inquirer-date"><?= date('d.m.Y', strtotime($question->created_at)) ?></div>
      </div>
   </div>
</div>
<?php } ?>