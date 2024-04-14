<?php

namespace app\components;

use yii\filters\Cors;

class CorsCustom extends Cors
{
    public function prepareHeaders($requestHeaders)
    {
        $responseHeaders = parent::prepareHeaders($requestHeaders);
        if (isset($responseHeaders['Access-Control-Allow-Origin'])) {

            $responseHeaders['Access-Control-Allow-Origin'] = '*';
        }
        return $responseHeaders;
    }
}
