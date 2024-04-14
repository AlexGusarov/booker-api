<?php

namespace app\components;

use yii\base\Behavior;
use yii\base\Event;
use yii\web\Application;

class SetCsrfValidation extends Behavior
{
    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'handleBeforeRequest',
        ];
    }

    public function handleBeforeRequest(Event $event)
    {
        if (preg_match('#^/api/#', \Yii::$app->request->url)) {
            \Yii::$app->request->enableCsrfValidation = false;
        }
    }
}
