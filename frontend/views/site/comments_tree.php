<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $comments array Array of common\models\Comment
**/

$showReplies = true;
$posts = false;
if(Yii::$app->controller->action->id == 'profile') {
    $showReplies = false;
    $posts = true;
}

\common\models\Comment::outCommentsTree($comments, 0, 0, $showReplies, $posts); 

?>
