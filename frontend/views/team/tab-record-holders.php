<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $team common\models\Team 
**/
?>


<?php if(isset($info['max_matches'])) { 
    $data = $info['max_matches'];
    ?>
<div class="default-box record-holder-box">
    <div class="box-header">
        <div class="box-title"><?= $data->title ?></div>
    </div>
    <div class="content">
        <?= $data->content ?>
    </div>
</div>
<?php } ?>

<?php if(isset($info['best_forwards'])) { 
    $data = $info['best_forwards'];
    ?>
<div class="default-box record-holder-box">
    <div class="box-header">
        <div class="box-title"><?= $data->title ?></div>
    </div>
    <div class="content">
        <?= $data->content ?>
    </div>
</div>
<?php } ?>

<?php if(isset($info['max_goals'])) { 
    $data = $info['max_goals'];
    ?>
<div class="default-box record-holder-box">
    <div class="box-header">
        <div class="box-title"><?= $data->title ?></div>
    </div>
    <div class="content">
        <?= $data->content ?>
    </div>
</div>
<?php } ?>
