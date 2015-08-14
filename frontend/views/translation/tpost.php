<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $post common\models\Post
**/
Yii::$app->formatter->locale = 'ru-RU';

$adminLink = '';
if(Yii::$app->user->can('admin')) {
  $adminLink = '<a class="admin-view-link" target="_blank" href="/admin/post/update/'.$post->id.'"></a>';
} 

$uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$site_logo = 'http://' . $_SERVER['HTTP_HOST'] . '/images/main_logo.svg';
$site_title = $post->title;

$commentsCount = $post->getCommentsCount();
?>

<div class="post-page match-online">
    <div class="post-container">

        <div class="auto-refresh">
            <div class="settings">
                <div class="text">Время автобновления:</div>
                <div class="select-refresh selectize-box">
                    <select name="refresh" id="select-refresh" placeholder="Не обновлять">
                        <option value="0" selected class="data-default">Не обновлять</option>
                        <option value="30">Каждые 30 секунд</option>
                        <option value="60">Каждые 60 секунд</option>
                        <option value="120">Каждые 120 секунд</option>
                    </select>
                </div>
            </div>
            <div class="actions">
                <a href="<?= Yii::$app->request->url ?>" class="button-refresh">Обновить</a>
                <div class="text timer">Обновление через <span class="time"></span> секунд</div>
            </div>
        </div>

        <div class="content">
            <?= $post->content ?>
        </div>
        <div class="clearfix"></div>
    </div>
 </div>