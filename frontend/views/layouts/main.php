<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

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
    <?php
        // test
        $page = array(
            'query' => 'front',
        );
        $page = (object)$page;
    ?>

    <div id="page">

        <header>
            <div class="header-wrapper">
                <div class="header-top-part">

                    <a href="/"><div class="logo"></div></a>

                    <div class="navigation-bar">
                        <?php if(Yii::$app->user->isGuest) { ?>

                            <div class="navigation-block-right">
                                <div class="navigation-block top-block">
                                    <a href="/user/register"><div class="registration">Регистрация</div></a>
                                    <a href="/user/login"><div class="sign-in">Войти</div></a>
                                </div>

                                <div class="navigation-block bottom-block">
                                    <a href="#">
                                        <div class="rules">Правила форума</div>
                                        <div class="icon"></div>
                                    </a>
                                </div>
                            </div>

                        <?php } else { ?>

                        <div class="logged-in">
                            <div class="photo">
                                <a href="/user/profile"><img src="/images/papos.jpg"></a>
                            </div>
                            <div class="main-functions">
                                <div class="name"><?= Yii::$app->user->getDisplayName() ?></div>
                                <a href="#">
                                    <div class="create-post">
                                        Создать пост
                                        <div class="icon"></div>
                                    </div>

                                </a>
                                <a href="/user/profile"><div class="link-to-cabinet">Личный Кабинет</div></a>
                            </div>
                            <a href="/user/logout">
                                <div class="logout">
                                    <div class="icon"></div>
                                </div>
                            </a>
                        </div>

                        <?php } ?>

                        <div class="social-buttons">
                            <a href="#"><div class="button youtube"></div></a>
                            <a href="#"><div class="button vk"></div></a>
                            <a href="#"><div class="button twitter"></div></a>
                            <a href="#"><div class="button fb"></div></a>
                            <a href="#"><div class="button rss"></div></a>
                        </div>
                    </div>

                </div>

                <div class="menu">
                    <ul>
                        <a href="#"><li class="special-project">Спецпроект</li></a>
                        <a href="<?= Url::to(['site/news']) ?>"><li class="<?= (Url::to(['site/news']) == Url::current()) ? 'current-page' : '' ?>">Новости</li></a>
                        <a href="#"><li>Команда</li></a>
                        <a href="<?= Url::to(['site/matches']) ?>"><li class="<?= (Url::to(['site/matches']) == Url::current()) ? 'current-page' : '' ?>">Матчи</li></a>
                        <a href="#"><li>Трансферы</li></a>
                        <a href="#"><li>Блоги</li></a>
                        <a href="#"><li>Фото</li></a>
                        <a href="#"><li>Видео</li></a>
                    </ul>

                    <div class="search">
                        <form action="" method="post">
                            <textarea rows="1" cols="45" name="text" class="search-textarea" style="overflow:hidden" placeholder="Поиск"></textarea>
                        </form>
                        <div class="search-icon"></div>
                    </div>
                </div>

                <div class="google-banner-main">
                    <img src="/images/730-1.png">
                </div>

                <div class="breadcrumbs">
                    <div class="header">Главное</div>
                    <div class="arrow"></div>
                    <a href="#"><div class="tag">Ярмоленко</div></a>
                    <a href="#"><div class="tag">Антунеш</div></a>
                    <a href="#"><div class="tag">Выборы президента ФФУ</div></a>
                    <a href="#"><div class="tag">Генгам</div></a>
                    <a href="#"><div class="tag">Эвертон</div></a>
                    <a href="#"><div class="tag">Скрипник</div></a>
                    <a href="#"><div class="tag">Все теги</div></a>
                </div>

            </div>
        </header>

        <div id="wrapper">
            <?= $content ?>
        </div>

        <footer id="colophon" class="site-footer" role="contentinfo">

            <div class="footer-wrapper">
                <div class="google-banner-main" style="margin-top: 0;">
                    <img src="/images/730-2.png">
                </div>

                <div class="footer-bottom">
                    <div class="block-top">
                        <a href="#">
                            <div class="button rss"></div>
                            <div class="text">RSS</div>
                        </a>
                        <a href="#">
                            <div class="button inform"></div>
                            <div class="text">Дополнительная Информация</div>
                        </a>
                    </div>
                    <div class="text-bottom">
                        Copyright © 2001-2015 Dynamomania.com. При использовании материалов сайта гиперссылка на <a href=""><div class="link-to-main">www.dynamomania.com</div></a> обязательна.
                    </div>
                </div>
            </div>

        </footer>

    </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
