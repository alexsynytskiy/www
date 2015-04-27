<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="error-message">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <div class="description">
        The above error occurred while the Web server was processing your request. <br>
        Please contact us if you think this is a server error. Thank you.
    </div>

</div>