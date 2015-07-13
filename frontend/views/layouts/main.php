<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;
use common\models\SiteBlock;
use common\models\Banner;

use amnah\yii2\user\models\User;

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

    <!-- Preloadding animation START -->
    <div id="loading">
        <div id="loading-center">
            <div id="loading-center-absolute">
                <div class="object" id="object_one"></div>
                <div class="object" id="object_two"></div>
                <div class="object" id="object_three"></div>
            </div>
        </div>
    </div>
    <!-- Preloadding animation END -->

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

                        <?php 
                            $user = User::findOne(Yii::$app->user->id);
                            $avatar = $user->getAsset();
                        ?>
                        <div class="logged-in">
                            <div class="photo">
                                <a href="<?= Url::to(['/user/profile']) ?>"><img src="<?= $avatar->getFileUrl() ?>"></a>
                            </div>
                            <div class="main-functions">
                                <div class="name"><?= $user->getDisplayName() ?></div>
                                <a href="<?= Url::to(['/post/add']) ?>">
                                    <div class="create-post">
                                        Создать пост
                                        <div class="icon"></div>
                                    </div>

                                </a>
                                <a href="<?= Url::to(['/user/profile']) ?>"><div class="link-to-cabinet">Личный Кабинет</div></a>
                            </div>
                            <a href="<?= Url::to(['/user/logout']) ?>">
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
                        <!-- <a href="#"><li class="special-project">Спецпроект</li></a> -->
                        <a href="<?= Url::to(['/site/news']) ?>">
                            <li class="<?= Yii::$app->controller->action->id == 'news' ? 'current-page' : '' ?>">Новости</li>
                        </a>
                        <?php 
                            $teamControllers = [
                                'team',
                                'player',
                                'coach',
                            ];
                        ?>
                        <a href="<?= Url::to(['/site/team', 'tab' => 'composition']) ?>">
                            <li class="<?= in_array(Yii::$app->controller->action->id, $teamControllers) ? 'current-page' : '' ?>">Команда</li>
                        </a>
                        <?php 
                            $matchControllers = [
                                'matches',
                                'match-translation',
                                'match-protocol',
                                'match-report',
                                'match-news',
                                'match-videos',
                                'match-photos',
                            ];
                        ?>
                        <a href="<?= Url::to(['/site/matches']) ?>">
                            <li class="<?= in_array(Yii::$app->controller->action->id, $matchControllers) ? 'current-page' : '' ?>">Матчи</li>
                        </a>
                        <a href="<?= Url::to(['/site/transfers']) ?>">
                            <li class="<?= Yii::$app->controller->action->id == 'transfers' ? 'current-page' : '' ?>">Трансферы</li>
                        </a>
                        <a href="<?= Url::to(['/site/blogs']) ?>">
                            <li class="<?= Yii::$app->controller->action->id == 'blogs' ? 'current-page' : '' ?>">Блоги</li>
                        </a>
                        <a href="<?= Url::to(['/site/photos']) ?>">
                            <li class="<?= Yii::$app->controller->action->id == 'photos' ? 'current-page' : '' ?>">Фото</li>
                        </a>
                        <a href="<?= Url::to(['/site/videos']) ?>">
                            <li class="<?= Yii::$app->controller->action->id == 'videos' ? 'current-page' : '' ?>">Видео</li>
                        </a>
                    </ul>

                    <div class="search">
                        <form action="/search" method="get">
                            <input type="text" name="q" class="search-textarea" placeholder="Поиск">
                        </form>
                        <div class="search-icon"></div>
                    </div>
                </div>

                <div class="top-banners-area">
                    <?php 
                        $bannerBlock = SiteBlock::getBanner(Banner::REGION_TOP);
                        if($bannerBlock) {
                            echo $this->render($bannerBlock['view'], isset($bannerBlock['data']) ? $bannerBlock['data'] : []);
                        }    
                    ?>
                </div>

                <div class="breadcrumbs">
                    <div class="header">Главное</div>
                    <div class="arrow"></div>
                    <?= \common\models\TopTag::outTop6Links() ?>
                </div>

            </div>
        </header>

        <div id="wrapper">
            <?= $content ?>
        </div>

        <footer id="colophon" class="site-footer" role="contentinfo">
            <div class="footer-wrapper">

                <div class="bottom-banners-area">
                    <?php 
                        $bannerBlock = SiteBlock::getBanner(Banner::REGION_BOTTOM);
                        if($bannerBlock) {
                            echo $this->render($bannerBlock['view'], isset($bannerBlock['data']) ? $bannerBlock['data'] : []);
                        }
                    ?>
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
