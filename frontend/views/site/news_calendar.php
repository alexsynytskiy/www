<?php
/**
 * @var $this yii\web\View
 * @var $newsDataProvider yii\data\ActiveDataProvider
 * @var $date string Selected date
**/
$currentYear = (int)date('Y',time());
?>

<div class="date-news-search calendar">
    <div class="calendar-title">
        <div class="text">Выбрать другую дату <div>с помощью календаря: </div></div>
        <a class="toggle-button toggle-show" href="javascript:void(0)" data-target="top-calendar">
            <div class="calendar-picker">
                <div class="icon"></div>
            </div>
        </a>
    </div>
    <div class="select-day">
        <span class="slabel">Дни: </span>
        <?php
            $selectedTime = $date ? strtotime($date) : time();
            $selectedTime += 60*60*24*2;
            for($i = 4; $i >= 0; $i--)
            {
                $dateValue = date('d.m.Y', $selectedTime - 60*60*24*$i);
                $dateText = Yii::$app->formatter->asDate($selectedTime - 60*60*24*$i, 'dd MMMM');
            ?>
                <a class="o-day" href="?date=<?= $dateValue ?>"><?= $dateText ?></a>
            <?php
            }   
        ?>
    </div>
    <div id="top-calendar" style="display: none;">
        <div class="header">
            <div class="select-year">
                <span class="slabel">Года: </span>
                <?php for($year = $currentYear; $year >= 2001; $year--) { ?>
                    <span class="o-year <?= ($year == $currentYear) ? 'active' : '' ?>"><?= $year ?></span>
                <?php } ?>
            </div>
            <div class="current-year"><div><?= $currentYear ?></div></div>
        </div>
        <div class="content"></div>
    </div>
    <?php 
        if($date) { 
            $this->registerJs(
                "var calendarDate = '".date('m-d-Y', strtotime($date))."';",
                \yii\web\View::POS_BEGIN,
                'my-options');
        } 
    ?>
</div>