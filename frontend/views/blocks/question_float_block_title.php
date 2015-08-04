<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $question common\models\Question
 * @var $answers array of common\models\Question
**/

$allVotes = $question->voutes;
$maxMark = 0;
foreach ($answers as $answer) {
  if($answer->mark > $maxMark) $maxMark = $answer->mark;
}
$maxMark = $maxMark ? $maxMark : 10;
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
        <a href="<?= Url::to('/inquirers') ?>"><div class="box-link">Все опросы:</div></a>
        <?php } ?>
    </div>
    <div class="box-content">
      <a href="/inquirers/<?= $question->id ?>">
        <div class="inquirer-theme"><?= $question->title ?></div>
      </a>
      <div class="inquirer-description">
        <div class="vote-count">Всего голосов <span><?= $allVotes ?></span></div>
        <div class="inquirer-date"><?= date('d.m.Y', strtotime($question->created_at)) ?></div>
      </div>
   </div>
</div>
<?php } ?>