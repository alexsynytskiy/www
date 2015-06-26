<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $team common\models\Team 
**/
?>

<div class="team-navigation">
    <a href="<?= Url::to('/team/'.$team->id.'/info') ?>">
        <div class="button info">
            Официальная информация
            <div class="icon"></div>
        </div>
    </a>
     <a href="<?= Url::to('/team/'.$team->id.'/composition') ?>">
         <div class="button composition">
            Составы команд
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/team/'.$team->id.'/achievements') ?>">
        <div class="button achievements">
            Достижения
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/team/'.$team->id.'/record-holders') ?>">
        <div class="button record-holders">
            Рекордсмены
            <div class="icon"></div>
        </div>
    </a>
    <div class="clearfix"></div>
</div>