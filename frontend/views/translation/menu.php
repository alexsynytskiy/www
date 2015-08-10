<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $match common\models\Match 
**/

$tab = Yii::$app->controller->action->id;
?>

<div class="match-navigation">
    <a id="text-translation-link" href="<?= Url::to('/match/'.$match->id) ?>">
        <div class="button translation <?= $tab == 'match-translation' ? 'active' : '' ?>">
            Трансляция
            <div class="icon"></div>
        </div>
    </a>
     <a href="<?= Url::to('/match/'.$match->id.'/protocol') ?>">
         <div class="button protocol <?= $tab == 'match-protocol' ? 'active' : '' ?>">
            Протокол
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/match/'.$match->id.'/report') ?>">
        <div class="button report <?= $tab == 'match-report' ? 'active' : '' ?>">
            Отчёт
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/match/'.$match->id.'/news') ?>">
        <div class="button other <?= $tab == 'match-news' ? 'active' : '' ?>">
            После матча
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/match/'.$match->id.'/photos') ?>">
        <div class="button photo <?= $tab == 'match-photos' ? 'active' : '' ?>">
            Фото
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/match/'.$match->id.'/videos') ?>">
        <div class="button video <?= $tab == 'match-videos' ? 'active' : '' ?>">
            Видео
            <div class="icon"></div>
        </div>
    </a>
    <div class="clearfix"></div>
</div>