<?php

use common\components\PostmarkComponent;
use common\components\SonarApiComponent;
use common\components\HubspotApiComponent;
use yii\caching\FileCache;
use yii\queue\db\Queue;

$params = array_merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'name' => $params['company_name'] . ' - ' . $params['product_name'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
        'sonar' => [
            'class' => SonarApiComponent::class,
            'baseUrl' => $params['sonar.url'] . '/api/graphql',
            'bearerToken' => $params['sonar.bearerToken'],
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
