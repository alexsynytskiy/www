<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $team common\models\Team 
 * @var $info common\models\MainInfo 
**/
?>


<div class="achievements-container">

    <?php if(isset($info['cups'])) { 
        $data = $info['cups'];
        ?>
    <div class="default-box">
        <div class="box-header">
            <div class="box-title"><?= $data->title ?></div>
        </div>
        <div class="content">
            <?= $data->content ?>
        </div>
    </div>
    <?php } ?>

    <?php if(isset($info['soviet_awards'])) { 
        $data = $info['soviet_awards'];
        ?>
    <div class="default-box">
        <div class="box-header">
            <div class="box-title"><?= $data->title ?></div>
        </div>
        <div class="content">
            <?= $data->content ?>
        </div>
    </div>
    <?php } ?>

    <?php if(isset($info['best_soviet_players'])) { 
        $data = $info['best_soviet_players'];
        ?>
    <div class="default-box">
        <div class="box-header">
            <div class="box-title"><?= $data->title ?></div>
        </div>
        <div class="content">
            <?= $data->content ?>
        </div>
    </div>
    <?php } ?>

    <?php if(isset($info['best_ukraine_players'])) { 
        $data = $info['best_ukraine_players'];
        ?>
    <div class="default-box">
        <div class="box-header">
            <div class="box-title"><?= $data->title ?></div>
        </div>
        <div class="content">
            <?= $data->content ?>
        </div>
    </div>
    <?php } ?>

    <?php if(isset($info['gold_ball'])) { 
        $data = $info['gold_ball'];
        ?>
    <div class="default-box">
        <div class="box-header">
            <div class="box-title"><?= $data->title ?></div>
        </div>
        <div class="content">
            <?= $data->content ?>
        </div>
    </div>
    <?php } ?>
    
</div>