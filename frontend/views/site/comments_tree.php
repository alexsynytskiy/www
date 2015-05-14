<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $comments array Array of common\models\Comment
**/

$showReplies = true;
if(isset($model)) {
    $comments = $model;
    $showReplies = false;
}

\common\models\Comment::outCommentsTree($comments, 0, 0, $showReplies); 

?>
