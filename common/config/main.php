<?php

use common\components\PostmarkComponent;
use common\components\GeminiApiComponent;
use yii\caching\FileCache;

$params = array_merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'name' => $params['product_name'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'gemini' => [
            'class' => GeminiApiComponent::class,
            'baseUrl' => $params['gemini.url'],
            'apiKey' => $params['gemini.key'],
            'model' => $params['gemini.model'],
        ],
        'postmark' => [
            'class' => PostmarkComponent::class,
            'serverToken' => $params['postmark.serverToken'],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ]
    ],
];
