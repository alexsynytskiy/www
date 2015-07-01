<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $match common\models\Match 
**/

$tab = Yii::$app->controller->action->id;
?>

<div class="match-navigation">
    <a id="text-translation-link" href="<?= Url::to('/translation/'.$match->id) ?>">
        <div class="button translation <?= $tab == 'translation' ? 'active' : '' ?>">
            Трансляция
            <div class="icon"></div>
        </div>
    </a>
     <a href="<?= Url::to('/translation/'.$match->id.'/protocol') ?>">
         <div class="button protocol <?= $tab == 'protocol' ? 'active' : '' ?>">
            Протокол
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/translation/'.$match->id.'/report') ?>">
        <div class="button report <?= $tab == 'report' ? 'active' : '' ?>">
            Отчёт
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/translation/'.$match->id.'/news') ?>">
        <div class="button other <?= $tab == 'translation-news' ? 'active' : '' ?>">
            Новости и Статьи
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/translation/'.$match->id.'/photo') ?>">
        <div class="button photo <?= $tab == 'translation-photo' ? 'active' : '' ?>">
            Фото
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/translation/'.$match->id.'/video') ?>">
        <div class="button video <?= $tab == 'translation-video' ? 'active' : '' ?>">
            Видео
            <div class="icon"></div>
        </div>
    </a>
    <div class="clearfix"></div>
</div>