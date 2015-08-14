<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = 'Страница не найдена';
?>
<style>
    html {
        height: 100%;
    }
    body {
        background: #fff;
        height: 100%;
    }
    body #wrapper {
        margin: 0;
        width: auto;
        height: 100%;
    }
</style>
<table class="error404-container">
    <tr>
        <td>
            <div class="site-error">

                <div class="image-error"></div>

                <div class="error-message"><?= $this->title ?></div>

                <div class="description">
                    Ошибка 404
                </div>

                <a href="/" class="button-back">Вернуться на главную</a>

            </div>
        </td>
    </tr>
</table>
