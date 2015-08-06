<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Dropdown;


/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/images/favicon.png" type="image/png" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            if(Yii::$app->user->can("admin")) {
                NavBar::begin([
                    //'' => 'Динамомания',
                    'brandLabel' => '<img src="/images/logoadmin.svg" class="img-responsive" style="margin-top: -7px; width: 250px;">',
                    'brandUrl' => Yii::$app->homeUrl,
                    'options' => [
                        'class' => 'navbar-inverse navbar-fixed-top',
                    ],
                ]);
                $menuItemsFirst = [];
                if(Yii::$app->user->can("changeUser") && Yii::$app->user->can("changeBan")) {
                    $menuItemsFirst = [
                        ['label' => 'Пользователи', 'items' => [
                            ['label' => 'Пользователи', 'url' => '/admin/user/admin'],
                            ['label' => 'Заблокированные IP адреса', 'url' => '/admin/banned-ip'],
                            ['label' => 'Жалобы', 'url' => '/admin/claim'],
                        ]]
                    ];
                }
                $menuItems = [
                    ['label' => 'Команда', 'items' => [
                        ['label' => 'Команды', 'url' => '/admin/team'],                        
                        ['label' => 'Игроки', 'url' => '/admin/player'],
                        ['label' => 'Карьеры игроков', 'url' => '/admin/career'],
                        ['label' => 'Тренеры', 'url' => '/admin/coach'],
                        ['label' => 'Тренеры по сезонам', 'url' => '/admin/team-coach'],
                        ['label' => 'Амплуа', 'url' => '/admin/amplua'],
                        ['label' => 'Игроки команд Динамо', 'url' => '/admin/contract'],                       
                        ['label' => 'Игроки других команд', 'url' => '/admin/membership'],
                        ['label' => 'Бомбардиры', 'url' => '/admin/forward'], 
                        ['label' => 'Трансферы', 'url' => '/admin/transfer'],
                        // ['label' => 'Типы трансферов', 'url' => '/admin/transfer-type'],                      
                        ['label' => 'Информация о команде', 'url' => '/admin/main-info'],
                    ]],
                    ['label' => 'Матчи', 'items' => [
                        ['label' => 'Матчи', 'url' => '/admin/match'],
                        ['label' => 'Справочник событий матча', 'url' => '/admin/match-event-type'],                   
                        ['label' => 'Турнирная таблица', 'url' => '/admin/tournament'],
                        ['label' => 'Настройки турнирной таблицы', 'url' => '/admin/tournament-settings'],
                        ['label' => 'Арбитры', 'url' => '/admin/arbiter'],
                        ['label' => 'Турниры', 'url' => '/admin/championship'],
                        ['label' => 'Типы лиг', 'url' => '/admin/league'],
                        ['label' => 'Этапы турнира', 'url' => '/admin/championship-part'],
                        ['label' => 'Сезоны', 'url' => '/admin/season'],
                        ['label' => 'Стадионы', 'url' => '/admin/stadium'],                        
                        ['label' => 'Страны', 'url' => '/admin/country'],         
                    ]],
                    ['label' => 'Публикации', 'items' => [
                        ['label' => 'Записи', 'url' => '/admin/post'],
                        ['label' => 'Альбомы', 'url' => '/admin/album'],
                        ['label' => 'Видеозаписи', 'url' => '/admin/video-post'],
                        ['label' => 'Комментарии', 'url' => '/admin/comment'],
                        ['label' => 'Источники', 'url' => '/admin/source'],
                        ['label' => 'Теги', 'url' => '/admin/tag'],
                        ['label' => 'Топовые теги', 'url' => '/admin/top-tag'],
                        ['label' => 'Подписка', 'url' => '/admin/subscribing'],
                        ['label' => 'Опросы', 'url' => '/admin/question'],
                        ['label' => 'Баннеры', 'url' => '/admin/banner'],
                        ['label' => 'Избранные блоги', 'url' => '/admin/selected-blog'],
                    ]],
                    ['label' => 'Сайт', 'url' => ('http://'.$_SERVER['HTTP_HOST']) ],
                ];
                $menuItems = array_merge($menuItemsFirst, $menuItems);
                if (Yii::$app->user->isGuest) {
                    $menuItems[] = ['label' => 'Вход', 'url' => ['/user/login']];
                } else {
                    $menuItems[] = [
                        'label' => 'Выход (' . Yii::$app->user->identity->username . ')',
                        'url' => ['/user/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ];
                }
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-right'],
                    'items' => $menuItems,
                ]);
                NavBar::end();
            }
        ?>

        <div class="container">
        <?php
            if(Yii::$app->user->can("admin")) {
                echo Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]);
            }
        ?>
        <?= $content ?>
        </div>
    </div>


    <?php if(Yii::$app->user->can("admin")) { ?>
        <footer class="footer">
            <div class="container">
            <p class="pull-left">&copy; Динамомания <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
            </div>
        </footer>
    <?php } ?>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
