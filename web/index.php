<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

$application = new yii\web\Application($config);

$application->on(yii\base\Application::EVENT_BEFORE_REQUEST, function ($event) {
    if (preg_match('#^/api/#', Yii::$app->request->url)) {
        Yii::$app->request->enableCsrfValidation = false;
    }
});

$application->run();
