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
    ],
];
