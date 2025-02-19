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
        /*'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db', // DB connection component or its config
            'tableName' => '{{%queue}}', // Table name
            'channel' => 'default', // Queue channel key
            'mutex' => 'yii\mutex\MysqlMutex', // Mutex used to sync queries
            'as log' => 'yii\queue\LogBehavior',
            //'as deadLetterBehavior' => \common\behaviors\DeadLetterQueue::class,
            'ttr' => 5 * 60, // Max time for anything job handling
            'attempts' => 3, // Max number of attempts
        ],*/
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
            ],
    ],
];
