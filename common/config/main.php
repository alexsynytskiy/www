<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language' => 'ru_RU',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'amnah\yii2\user\components\User',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '/'                                      => 'site/index',
                'news'                                   => 'site/news',
                'matches'                                => 'site/matches',
                'transfers/<id:\d+>'                     => 'site/transfer',
                'transfers'                              => 'site/transfers',
                'translation/<id:\d+>'                   => 'site/translation',
                'translation/<id:\d+>/protocol'          => 'site/protocol',
                'news/<id:\d+>-<slug>'                   => 'site/post',
                'blog/<id:\d+>-<slug>'                   => 'site/post',
                'inquirers'                              => 'site/inquirers',
                'user/profile'                           => 'user/default/profile',
                'user/edit'                              => 'user/default/account',
                '<controller:\w+>/<id:\d+>'              => '<controller>/view',
                '<controller:\w+>'                       => '<controller>/index',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
                //'module/<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                //'module/<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>',
            ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'ru',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
    ],
    'modules' => [
        'user' => [
            'class' => 'amnah\yii2\user\Module',
            // set custom module properties here ...
        ],
        'yii2images' => [
            'class' => 'rico\yii2images\Module',
            //be sure, that permissions ok
            //if you cant avoid permission errors you have to create "images" folder in web root manually and set 777 permissions
            'imagesStorePath' => '@frontend/web/images/store', //path to origin images
            'imagesCachePath' => '@frontend/web/images/cache', //path to resized copies
            'graphicsLibrary' => 'GD', //but really its better to use 'Imagick'
            'placeHolderPath' => '@webroot/images/placeHolder.png', // if you want to get placeholder when image not exists, string will be processed by Yii::getAlias
        ],
    ],
];
