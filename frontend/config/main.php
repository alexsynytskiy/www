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
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/'                                      => 'site/index',
                'news'                                   => 'site/news',
                'search'                                 => 'site/search',
                'matches'                                => 'site/matches',
                'transfers/<id:\d+>'                     => 'site/transfer',
                'transfers'                              => 'site/transfers',
                'match/<id:\d+>'                         => 'site/match-translation',
                'match/<id:\d+>/protocol'                => 'site/match-protocol',
                'match/<id:\d+>/report'                  => 'site/match-report',
                'match/<id:\d+>/news'                    => 'site/match-news',
                'player/<id:\d+>-<slug>'                 => 'site/player',
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
                // 'question/<action:\w+>'                  => 'site/question/<action>',
                'user/profile'                           => 'user/default/profile',
                'user/profile/<id:\d+>'                  => 'user/default/profile',
                'user/edit'                              => 'user/default/account',
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
