<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'mysql:host=mysql;dbname=app',
            'username' => 'app',
            'password' => '123',
            'charset' => 'utf8',
        ],
        'queue' => [
            'class' => \yii\queue\sync\Queue::class,
            'handle' => true, // whether tasks should be executed immediately
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
            ],
    ],
];
