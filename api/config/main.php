<?php

use yii\log\FileTarget;
use yii\rest\UrlRule;
use yii\web\JsonParser;
use yii\web\UrlManager;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'calorie-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => [
        'log' => [
            'class'   => '\yii\filters\ContentNegotiator',
            'formats' => [
                //  comment next line to use GII
                'application/json' => \yii\web\Response::FORMAT_JSON,
            ],
        ],
    ],
    'modules' => [],
    'components' => [
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation'   => false,
            'parsers' => [
                'application/json' => JsonParser::class,
            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => UrlManager::class,
            'enablePrettyUrl' => true,
            //'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => UrlRule::class, 'controller' => ['meal', 'auth']],
                'POST /meals/create-meal' => 'meal/create-meal',
                'GET /meals/get-daily-summary' => 'meal/get-daily-summary',
            ],
        ],
        'user' => [
            'identityClass' => \common\models\User::class,
            'enableSession'   => false,
            'enableAutoLogin' => false,
            'loginUrl'        => null,
        ],
    ],
    'params' => $params,
];
