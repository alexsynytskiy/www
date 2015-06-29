<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $team common\models\Team 
**/
?>

<div class="default-box team-image-box">
    <img class="team-image" src="http://dynamomania.dev/images/default_image.png">
    <div class="caption">
        <div class="left">
            <div class="title">Фото команды <?= $team->name ?></div>
            <div class="season">2014/15</div>
        </div>
        <div class="right">
            <div class="founded"><strong>Основан:</strong> 1 ноября 1927 года</div>
            <div class="colors"><strong>Цвета:</strong> бело-голубые</div>
        </div>
    </div>
</div>

<div class="default-box info-box leadership-box">
    <div class="icon-bar">
        <div class="icon"></div>
    </div>
    <div class="column">
        <div class="label">Президент:</div>
        <div class="value">Игорь Суркис</div>
        <div class="label">Генеральный директор:</div>
        <div class="value">Чезо Чохонелидзе</div>
        <div class="label">Вице-президенты:</div>
        <div class="value">
            Леонид Ашкенази<br>
            Михаил Петрашенко<br>
            Евгений Рашутин<br>
            Владимир Старовойт<br>
            Алексей Паламарчук<br>
            Алексей Семененко<br>
        </div>
    </div>
    <div class="column">
        <div class="label">Первый вице-президент:</div>
        <div class="value">Виталий Сивков</div>
        <div class="label">Спортивный директор:</div>
        <div class="value">Виталий Сивков</div>
        <div class="label">Директор по связям с общественностью:</div>
        <div class="value">Виталий Сивков</div>
        <div class="label">Руководитель службы пресс-аташе:</div>
        <div class="value">Виталий Сивков</div>
        <div class="label">Директор медиа-центра:</div>
        <div class="value">Виталий Сивков</div>
    </div>
</div>

<div class="default-box info-box main-info-box">
    <div class="icon-bar">
        <div class="icon"></div>
    </div>
    <div class="column">
        Основная информация
    </div>
    <div class="column">
        
    </div>
</div>

<div class="default-box info-box other-info-box">
    <div class="icon-bar">
        <div class="icon"></div>
    </div>
    <div class="column">
        Другая информация
    </div>
    <div class="column">
        
    </div>
</div>
