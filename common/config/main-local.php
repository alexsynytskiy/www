<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=myisam_dynamo',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        // 'sphinx' => [
        //     'class' => 'yii\sphinx\Connection',
        //     'dsn' => 'mysql:host=127.0.0.1;dbname=dynamo_sqlready;port=9306;',
        //     'username' => 'root',
        //     'password' => '',
        // ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            // 'transport' => [
            //     'class' => 'Swift_SmtpTransport',
            //     'host' => 'smtp.gmail.com',
            //     'username' => 'username@gmail.com',
            //     'password' => 'password',
            //     'port' => '587',
            //     'encryption' => 'tls',
            // ],
        ],
    ],
];
