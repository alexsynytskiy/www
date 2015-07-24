<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<style type="text/css">
    #wrapper {
        margin-top: 0;
    }
</style>
<table class="error-container"><tr><td>
    <div class="site-error">

        <h1><?= Html::encode($this->title) ?></h1>

        <div class="error-message">
            <?= nl2br(Html::encode($message)) ?>
        </div>

        <div class="description">
            Произошла ошибка при обработке вашего запроса. <br>
            Пожалуйста, свяжитесь с нами, если вы думаете, что это ошибка сервера. Спасибо.
        </div>

    </div>
</td></tr></table>
