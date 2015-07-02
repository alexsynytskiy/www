<?php
use yii\helpers\Url;

/** 
 * @var $this yii\web\View 
 * @var $username string
 * @var $posts array of common\models\Post 
 * @var $unsubscribeKey string
**/

?>
<p>Уважаемый пользователь,</p>

<p>Вы подписаны на ежедневную рассылку новостей от сайта Dynamomania.com</p>

<p>Важные и наиболее интересные статьи за последний время:</p>

<?php foreach ($posts as $post): ?>
    <div class="post" style="padding-left: 20px; margin-bottom: 10px;">
        <a href="<?= $post->getUrl() ?>"><?= $post->title ?></a>
    </div>
<?php endforeach ?>

<p>Для отписки от рассылки перейдите по <a href="<?= Url::to('/unsubscribe/'.$unsubscribeKey) ?>">этой ссылке</a></p>

<p>С уважением,</p>
<p>
    <a href="http://dynamomania.com">Динамомания</a>
</p>
