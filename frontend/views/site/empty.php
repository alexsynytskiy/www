<?php
/**
 * @var $this yii\web\View
 * @var $message string Message
**/

if(!isset($message)) $message = 'Ничего не найдено.';
?>

<div class="default-box empty-box">
    <div class="empty"><?= $message ?></div>
</div>