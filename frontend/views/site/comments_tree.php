<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $comments array Array of common\models\Comment
 * @var $options array Options
**/

if(!isset($options)) $options = [];

\common\models\Comment::outCommentsTree($comments, 0, $options); 

?>
