<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $match common\models\Match 
 * @var $matchEvents array of common\models\MatchEvent
 * @var $teamHomePlayers array of common\models\Composition 
 * @var $teamGuestPlayers array of common\models\Composition 
**/
?>

<?php echo $this->render('@frontend/views/translation/menu', compact('match')) ?>

<?php echo $this->render('@frontend/views/translation/match_preview', compact(
        'match', 
        'matchEvents',
        'teamHomePlayers',
        'teamGuestPlayers'
    )); ?>

<div class="match-arbiters default-box">
    <div class="box-header">
        <div class="box-title">Арбитры</div>
    </div>
    <div class="box-content">
        <div class="main-arbiter">
            <div class="column-title">Основной арбитр:</div>
            <div class="name">А. Жабченко (Симферополь)</div>
        </div>
        <div class="assist-arbiter">
            <div class="column-title">Ассистенты:</div>
            <div class="name">С. Шлончак (Черкассы)</div>
            <div class="name">А. Корнийко (Миргород)</div>
        </div>
        <div class="reserv-arbiter">
            <div class="column-title">Резервный арбитр:</div>
            <div class="name">Ю. Вакс (Симферополь)</div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="first-team default-box">
    <div class="box-header">
        <div class="box-title">Составы команд</div>
    </div>
    <div class="box-content" style="padding: 0;">
        <!-- start team-a -->
        <div class="team team-a">
            <div class="player">
                <div class="dest">вр</div>
                <div class="number">81</div>
                <div class="desc">
                    <a href="#"><div class="name">Владимир Дишленкович</div></a>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">3</div>
                <div class="desc">
                    <a href="#"><div class="name">Кристиан Вильягра</div></a>
                    <div class="yellow-card"></div><div class="time">85"</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">6</div>
                <div class="desc">
                    <a href="#"><div class="name">Марко Торсильери</div></a>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">17</div>
                <div class="desc">
                    <a href="#"><div class="name">Сергей Пшеничных</div></a>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">20</div>
                <div class="desc">
                    <a href="#"><div class="name">Марсиу Азеведо</div></a>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">22</div>
                <div class="desc">
                    <a href="#"><div class="name">Денис Кулаков</div></a>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">24</div>
                <div class="desc">
                    <a href="#"><div class="name">Айила Юссуф</div></a>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">8</div>
                <div class="desc">
                    <a href="#"><div class="name">Эдмар</div></a>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">85</div>
                <div class="desc">
                    <a href="#"><div class="name">Клейтон Шавьер</div></a>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">32</div>
                <div class="desc">
                    <a href="#"><div class="name">Олег Красноперов</div></a>
                    <div class="replacement"></div>
                    <div class="time">76"</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">нп</div>
                <div class="number">14</div>
                <div class="desc">
                    <a href="#"><div class="name">Владимир Гоменюк</div></a>
                    <div class="replacement"></div>
                    <div class="time">53"</div>
                </div>
            </div>
        </div><!-- end team-a -->
        <div class="delimiter"></div>
        <!-- start team-b -->
        <div class="team team-b">
            <div class="player">
                <div class="dest">вр</div>
                <div class="number">1</div>
                <div class="desc">
                    <a href="#"><div class="name">Александр Шовковский</div></a>
                    <div class="yellow-card"></div>
                    <div class="time">60"</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">2</div>
                <div class="desc">
                    <div class="name">Данило Силва</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">6</div>
                <div class="desc">
                    <div class="name">Александар Драгович</div>
                    <div class="replacement"></div>
                    <div class="time">46"</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">24</div>
                <div class="desc">
                    <div class="name">Домагой Вида</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">27</div>
                <div class="desc">
                    <div class="name">Евгений Макаренко</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">9</div>
                <div class="desc">
                    <div class="name">Роман Безус</div>
                    <div class="replacement"></div>
                    <div class="time">63"</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">17</div>
                <div class="desc">
                    <div class="name">Сергей Рыбалка</div>
                    <div class="yellow-card"></div>
                    <div class="time">27"</div>
                    <div class="red-card"></div>
                    <div class="time">86"</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">45</div>
                <div class="desc">
                    <div class="name">Владислав Калитвинцев</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">90</div>
                <div class="desc">
                    <div class="name">Юнес Бельанда</div>
                    <div class="yellow-card"></div>
                    <div class="time">68"</div>
                    <div class="replacement"></div>
                    <div class="time">82"</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">10</div>
                <div class="desc">
                    <div class="name">Андрей Ярмоленко</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">нп</div>
                <div class="number">22</div>
                <div class="desc">
                    <div class="name">Артем Кравец</div>
                </div>
            </div>
        </div><!-- end team-b -->
    </div>
</div>

<div class="first-team default-box">
    <div class="box-header">
        <div class="box-title">Запасные</div>
    </div>
    <div class="box-content" style="padding: 0;">
        <!-- start team-a -->
        <div class="team team-a">
            <div class="player">
                <div class="dest">вр</div>
                <div class="number">35</div>
                <div class="desc">
                    <div class="name">Богдан Шуст</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">4</div>
                <div class="desc">
                    <div class="name">Андрей Березовчук</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">30</div>
                <div class="desc">
                    <div class="name">Папа Гуйе</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">19</div>
                <div class="desc">
                    <div class="name">Хуан Мануэль Торрес</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">43</div>
                <div class="desc">
                    <div class="name">Юрий Ткачук</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">82</div>
                <div class="desc">
                    <div class="name">Павел Ребенок</div>
                    <div class="replacement"></div>
                    <div class="time">76"</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">50</div>
                <div class="desc">
                    <div class="name">Джексон Коэлью</div>
                    <div class="replacement"></div>
                    <div class="time">53"</div>
                </div>
            </div>
        </div><!-- end team-a -->
        <div class="delimiter"></div>
        <!-- start team-b -->
        <div class="team team-b">
            <div class="player">
                <div class="dest">вр</div>
                <div class="number">23</div>
                <div class="desc">
                    <div class="name">Александр Рыбка</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">3</div>
                <div class="desc">
                    <div class="name">Евгений Селин</div>
                    <div class="replacement"></div>
                    <div class="time">82"</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">28</div>
                <div class="desc">
                    <div class="name">Бенуа Тремулинас</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">16</div>
                <div class="desc">
                    <div class="name">Сергей Сидорчук</div>
                    <div class="replacement"></div>
                    <div class="time">46"</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">зщ</div>
                <div class="number">19</div>
                <div class="desc">
                    <div class="name">Денис Гармаш</div>
                    <div class="replacement"></div>
                    <div class="time">63"</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">20</div>
                <div class="desc">
                    <div class="name">Олег Гусев</div>
                </div>
            </div>
            <div class="player">
                <div class="dest">пз</div>
                <div class="number">29</div>
                <div class="desc">
                    <div class="name">Виталий Буяльский</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="match-statistics default-box">
    <div class="box-header">
        <div class="box-title">Статистика матча</div>
    </div>
    <div class="box-content">
        <div class="statistics">
            <div class="teams">
                <div class="home">"Металлист"</div>
                <div class="visitors">"Динамо" Киев</div>
                <div class="clearfix"></div>
            </div>
            <div class="parameter">
                <div class="header">
                    <?php
                        $house_value = 7;
                        $visitors_value = 7;
                        $width = 590;

                        $average = $house_value + $visitors_value;

                        $house_width = ($width / $average) * $house_value;
                        $visitors_width = ($width / $average) * $visitors_value;
                    ?>
                    <div class="house-value"><?php echo $house_value; ?></div>
                    <div class="parameter-name">Удары по воротам</div>
                    <div class="visitors-value"><?php echo $visitors_value; ?></div>
                    <div class="clearfix"></div>
                </div>
                <div class="stripes">
                    <div class="home" style="width:<?php echo $house_width; ?>px;"></div>
                    <div class="visitors" style="width:<?php echo $visitors_width; ?>px;"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="parameter">
                <div class="header">
                    <?php
                        $house_value = 3;
                        $visitors_value = 5;

                        $average = $house_value + $visitors_value;

                        $house_width = ($width / $average) * $house_value;
                        $visitors_width = ($width / $average) * $visitors_value;
                    ?>
                    <div class="house-value"><?php echo $house_value; ?></div>
                    <div class="parameter-name">Удары в сторону ворот</div>
                    <div class="visitors-value"><?php echo $visitors_value; ?></div>
                    <div class="clearfix"></div>
                </div>
                <div class="stripes">
                    <div class="home" style="width:<?php echo $house_width; ?>px;"></div>
                    <div class="visitors" style="width:<?php echo $visitors_width; ?>px;"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="parameter">
                <div class="header">
                    <?php
                        $house_value = 1;
                        $visitors_value = 4;

                        $average = $house_value + $visitors_value;

                        $house_width = ($width / $average) * $house_value;
                        $visitors_width = ($width / $average) * $visitors_value;
                    ?>
                    <div class="house-value"><?php echo $house_value; ?></div>
                    <div class="parameter-name">Офсайды</div>
                    <div class="visitors-value"><?php echo $visitors_value; ?></div>
                    <div class="clearfix"></div>
                </div>
                <div class="stripes">
                    <div class="home" style="width:<?php echo $house_width; ?>px;"></div>
                    <div class="visitors" style="width:<?php echo $visitors_width; ?>px;"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="parameter">
                <div class="header">
                    <?php
                        $house_value = 5;
                        $visitors_value = 1;

                        $average = $house_value + $visitors_value;

                        $house_width = ($width / $average) * $house_value;
                        $visitors_width = ($width / $average) * $visitors_value;
                    ?>
                    <div class="house-value"><?php echo $house_value; ?></div>
                    <div class="parameter-name">Угловые</div>
                    <div class="visitors-value"><?php echo $visitors_value; ?></div>
                    <div class="clearfix"></div>
                </div>
                <div class="stripes">
                    <div class="home" style="width:<?php echo $house_width; ?>px;"></div>
                    <div class="visitors" style="width:<?php echo $visitors_width; ?>px;"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="parameter">
                <div class="header">
                    <?php
                        $house_value = 16;
                        $visitors_value = 13;

                        $average = $house_value + $visitors_value;

                        $house_width = ($width / $average) * $house_value;
                        $visitors_width = ($width / $average) * $visitors_value;
                    ?>
                    <div class="house-value"><?php echo $house_value; ?></div>
                    <div class="parameter-name">Фолы</div>
                    <div class="visitors-value"><?php echo $visitors_value; ?></div>
                    <div class="clearfix"></div>
                </div>
                <div class="stripes">
                    <div class="home" style="width:<?php echo $house_width; ?>px;"></div>
                    <div class="visitors" style="width:<?php echo $visitors_width; ?>px;"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="parameter">
                <div class="header">
                    <?php
                        $house_value = 1;
                        $visitors_value = 4;

                        $average = $house_value + $visitors_value;

                        $house_width = ($width / $average) * $house_value;
                        $visitors_width = ($width / $average) * $visitors_value;
                    ?>
                    <div class="house-value"><?php echo $house_value; ?></div>
                    <div class="parameter-name">Жёлтые карточки</div>
                    <div class="visitors-value"><?php echo $visitors_value; ?></div>
                    <div class="clearfix"></div>
                </div>
                <div class="stripes">
                    <div class="home" style="width:<?php echo $house_width; ?>px;"></div>
                    <div class="visitors" style="width:<?php echo $visitors_width; ?>px;"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="parameter">
                <div class="header">
                    <?php
                        $house_value = 0;
                        $visitors_value = 1;

                        $average = $house_value + $visitors_value;

                        $house_width = ($width / $average) * $house_value;
                        $visitors_width = ($width / $average) * $visitors_value;
                    ?>
                    <div class="house-value"><?php echo $house_value; ?></div>
                    <div class="parameter-name">Красные карточки</div>
                    <div class="visitors-value"><?php echo $visitors_value; ?></div>
                    <div class="clearfix"></div>
                </div>
                <div class="stripes">
                    <div class="home" style="width:<?php echo $house_width; ?>px;"></div>
                    <div class="visitors" style="width:<?php echo $visitors_width; ?>px;"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>