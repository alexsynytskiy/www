<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $match common\models\Match 
**/
?>

<div class="match-navigation">
    <a id="text-translation-link" href="<?= Url::to('/translation/'.$match->id) ?>">
        <div class="button translation">
            Трансляция
            <div class="icon"></div>
        </div>
    </a>
     <a href="<?= Url::to('/translation/'.$match->id.'/protocol') ?>">
         <div class="button protocol">
            Протокол
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/translation/'.$match->id.'/report') ?>">
        <div class="button report">
            Отчёт
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/translation/'.$match->id.'/news') ?>">
        <div class="button other">
            Новости и Статьи
            <div class="icon"></div>
        </div>
    </a>
    <div class="clearfix"></div>
</div>