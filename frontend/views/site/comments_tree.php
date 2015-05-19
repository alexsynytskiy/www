<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $comments array Array of common\models\Comment
**/

$options = [];
if(Yii::$app->controller->action->id == 'profile') {
    $options = [
        'showReplies' => false,
        'showReplyButton' => false,
        'posts' => true,
    ];
}

\common\models\Comment::outCommentsTree($comments, 0, $options); 

?>
