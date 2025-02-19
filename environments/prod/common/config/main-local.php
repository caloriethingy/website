<?php

use yii\db\Connection;
use yii\queue\db\Queue;
use yii\symfonymailer\Mailer;

return [
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=app',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'queue' => [
            'class' => Queue::class,
            'db' => 'db', // DB connection component or its config
            'tableName' => '{{%queue}}', // Table name
            'channel' => 'default', // Queue channel key
            'mutex' => 'yii\mutex\MysqlMutex', // Mutex used to sync queries
            'as log' => 'yii\queue\LogBehavior',
            //'as deadLetterBehavior' => \common\behaviors\DeadLetterQueue::class,
            'ttr' => 5 * 60, // Max time for anything job handling
            'attempts' => 3, // Max number of attempts
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@common/mail',
        ],
    ],
];
