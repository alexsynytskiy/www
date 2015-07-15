<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    // 'on beforeRequest' => function () {
    //     $app = Yii::$app;
    //     $pathInfo = $app->request->pathInfo;
    //     if (!empty($pathInfo) && substr($pathInfo, -1) == '/') {
    //         $newPathInfo = mb_substr($pathInfo, 0, mb_strlen($pathInfo, 'UTF-8') - 1, 'UTF-8');
    //         $app->response->redirect('/' . $newPathInfo, 301);
    //     }
    // },
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            // 'suffix' => '/',
            'rules' => [
                '/'                                      => 'site/index',
                'news'                                   => 'site/news',
                'photos'                                 => 'site/photos',
                'album/<id:\d+>-<slug>'                  => 'site/album',
                'album/load-images'                      => 'site/album-load-images',
                'album/<album_id:\d+>-<slug>/<photo_id:\d+>' => 'site/photo',
                'blogs'                                  => 'site/blogs',
                'info'                                   => 'site/info',
                'contacts'                               => 'site/contacts',
                'tags'                                   => 'site/tags',
                'blogs/<id:\d+>'                         => 'site/blogs',
                'search'                                 => 'site/search',
                'matches'                                => 'site/matches',
                'transfers/<id:\d+>'                     => 'site/transfer',
                'transfers'                              => 'site/transfers',
                'match/<id:\d+>'                         => 'site/match-translation',
                'match/<id:\d+>/protocol'                => 'site/match-protocol',
                'match/<id:\d+>/report'                  => 'site/match-report',
                'match/<id:\d+>/news'                    => 'site/match-news',
                'match/<id:\d+>/photos'                  => 'site/match-photos',
                'player/<id:\d+>-<slug>'                 => 'site/player',
                'coach/<id:\d+>-<slug>'                  => 'site/coach',
                'match/<id:\d+>/photo'                   => 'site/match-photo',
                'match/<id:\d+>/video'                   => 'site/match-video',
                'team/<tab:[\w-]+>/<id:\d+>'             => 'site/team',
                'team/<tab:[\w-]+>'                      => 'site/team',
                'news/<id:\d+>-<slug>'                   => 'site/post',
                'blog/<id:\d+>-<slug>'                   => 'site/post',
                'post/add'                               => 'site/post-add',
                'post/edit/<id:\d+>'                     => 'site/post-edit',
                'inquirers'                              => 'site/inquirers',
                'complain/<id:\d+>'                      => 'site/complain',
                'user/profile'                           => 'user/default/profile',
                'user/edit'                              => 'user/default/account',
                'unsubscribe/<key>'                      => 'site/unsubscribe',
                '<controller:\w+>/<id:\d+>'              => '<controller>/view',
                '<controller:\w+>'                       => '<controller>/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
                'user/<controller:\w+>/<action:\w+>/<id:\d+>'  => 'user/<controller>/<action>',
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'general/error',
        ],
        'request'=>[
            'class' => 'common\components\Request',
            'web'=> '/frontend/web'
        ],
    ],
    'params' => $params,
];
