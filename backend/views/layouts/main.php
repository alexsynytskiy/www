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
                    'brandLabel' => '<img src="/images/logotype_admin.png" class="img-responsive" style="margin-top: -7px; width: 250px;">',
                    'brandUrl' => Yii::$app->homeUrl,
                    'options' => [
                        'class' => 'navbar-inverse navbar-fixed-top',
                    ],
                ]);
                $menuItems = [
                    ['label' => 'Пользователи', 'url' => '/admin/user/admin'],
                    ['label' => 'Трансферы', 'items' => [
                        ['label' => 'Трансферы', 'url' => '/admin/transfer'],
                        ['label' => 'Типы трансферов', 'url' => '/admin/transfer-type'],
                    ]],
                    ['label' => 'Команда', 'items' => [
                        ['label' => 'Команды', 'url' => '/admin/team'],
                        ['label' => 'Турнирная таблица', 'url' => '/admin/tournament'],
                        ['label' => 'Игроки', 'url' => '/admin/player'],                        
                        ['label' => 'Амплуа', 'url' => '/admin/amplua'],
                    ]],
                    ['label' => 'Матчи', 'items' => [
                        ['label' => 'Матчи', 'url' => '/admin/match'],
                        ['label' => 'Арбитры', 'url' => '/admin/arbiter'],
                        ['label' => 'Турниры', 'url' => '/admin/championship'],
                        ['label' => 'Типы лиг', 'url' => '/admin/league'],
                        ['label' => 'Этап соревнования', 'url' => '/admin/championship-part'],
                        ['label' => 'Стадионы', 'url' => '/admin/stadium'],
                        ['label' => 'События матча', 'url' => '/admin/match-event-type'],
                    ]],
                    ['label' => 'Страны', 'url' => '/admin/country'],
                    ['label' => 'Публикации', 'items' => [
                        ['label' => 'Записи', 'url' => '/admin/post'],
                        ['label' => 'Альбомы', 'url' => '/admin/album'],
                        ['label' => 'Комментарии', 'url' => '/admin/comment'],
                        ['label' => 'Источники', 'url' => '/admin/source'],
                        ['label' => 'Теги', 'url' => '/admin/tag'],
                    ]],
                    ['label' => 'Сайт', 'url' => ('http://'.$_SERVER['HTTP_HOST']) ],
                ];
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
