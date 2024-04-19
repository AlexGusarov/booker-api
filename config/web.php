<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 's6EyciobEfwVjm69vwL2FwlRY0-rWdmf',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'as setCsrfValidation' => [
                'class' => 'app\components\SetCsrfValidation',
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
            'on beforeSend' => function ($event) {
                $response = $event->sender;

                $response->headers->add('Access-Control-Allow-Origin', '*');
                $response->headers->add('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
                $response->headers->add('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept, Authorization');
                $response->headers->add('Access-Control-Allow-Credentials', 'true');

                if ($response->data !== null && !$response->isSuccessful) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                        'message' => $response->statusText,
                        'code' => $response->statusCode,
                    ];
                }
            },

        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                // Цель для логирования в файл
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                // Цель для логирования отладочной информации в отдельный файл
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories' => ['debug*'],
                    'logFile' => '@runtime/logs/debug.log',
                ],
                // Цель для вывода логов в stdout
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logVars' => [], // Не включать $_GET, $_POST и т.д.
                    'exportInterval' => 1, // Выводить логи сразу
                    'categories' => ['console*'],
                    'logFile' => 'php://stdout', // Вывод в stdout
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['book', 'author', 'language'],
                    'prefix' => 'api',
                    'extraPatterns' => [
                        'GET search' => 'search',
                        'GET genres' => 'genres'
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,


];

return $config;
