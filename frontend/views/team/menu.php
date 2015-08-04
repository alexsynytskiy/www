<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $team common\models\Team 
 * @var $tab string Active tab
**/

$adminLink = '';
if(Yii::$app->user->can('admin') && $tab != 'composition') {
  $adminLink = '<a class="admin-view-link" target="_blank" href="/admin/main-info/"></a>';
} 
?>

<div class="team-navigation navbar">
    <a href="<?= Url::to('/team/composition/'.$team->id) ?>">
         <div class="button composition <?= $tab == 'composition' ? 'active' : '' ?>">
            Составы команд
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/team/info') ?>">
        <div class="button info <?= $tab == 'info' ? 'active' : '' ?>">
            Официальная информация
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/team/achievements') ?>">
        <div class="button achievements <?= $tab == 'achievements' ? 'active' : '' ?>">
            Достижения
            <div class="icon"></div>
        </div>
    </a>
    <a href="<?= Url::to('/team/record-holders') ?>">
        <div class="button record-holders <?= $tab == 'record-holders' ? 'active' : '' ?>">
            Рекордсмены
            <div class="icon"></div>
        </div>
    </a>
    <?= $adminLink ?>
    <div class="clearfix"></div>
</div>