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
                    'brandLabel' => 'Динамомания',
                    'brandUrl' => Yii::$app->homeUrl,
                    'options' => [
                        'class' => 'navbar-inverse navbar-fixed-top',
                    ],
                ]);
                $menuItems = [
                    ['label' => 'Записи', 'url' => '/admin/post'],
                    ['label' => 'Альбомы', 'url' => '/admin/album'],
                    ['label' => 'Пользователи', 'url' => '/admin/user/admin'],
                    ['label' => 'Настройки', 'items' => [
                        ['label' => 'Теги', 'url' => '/admin/tag'],
                        ['label' => 'Источники', 'url' => '/admin/source'],
                        ['label' => 'Комментарии', 'url' => '/admin/comment'],
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
