<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $team common\models\Team 
 * @var $info common\models\MainInfo 
**/
?>


<?php if(isset($info['image'])) { 
    $data = $info['image'];
    ?>
<div class="default-box team-image-box">
    <div class="team-image">
        <?= $data->content ?>
    </div>
    <div class="caption">
        <div class="left">
            <div class="title"><?= $data->title ?></div>
        </div>
        <div class="right">
            <div class="founded"><strong>Основан:</strong> 1 ноября 1927 года</div>
            <div class="colors"><strong>Цвета:</strong> бело-голубые</div>
        </div>
    </div>
</div>
<?php } ?>

<?php if(isset($info['leadership'])) { ?>
<div class="default-box info-box leadership-box">
    <div class="icon-bar">
        <div class="icon"></div>
    </div>
    <div class="content">
        <?php
            $data = $info['leadership'];
            echo $data->content;
        ?>
    </div>
</div>
<?php } ?>

<?php if(isset($info['general_info'])) { ?>
<div class="default-box info-box main-info-box">
    <div class="icon-bar">
        <div class="icon"></div>
    </div>
    <div class="content">
        <?php
            $data = $info['general_info'];
            echo $data->content;
        ?>
    </div>
</div>
<?php } ?>

<?php if(isset($info['other_info'])) { ?>
<div class="default-box info-box other-info-box">
    <div class="icon-bar">
        <div class="icon"></div>
    </div>
    <div class="content">
        <?php
            $data = $info['other_info'];
            echo $data->content;
        ?>
    </div>
</div>
<?php } ?>
