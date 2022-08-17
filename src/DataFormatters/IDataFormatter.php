<?php

namespace Brezgalov\BaseApiClient\DataFormatters;

use yii\httpclient\Response;

interface IDataFormatter
{
    /**
     * @param Response $response
     * @return mixed
     */
    public static function format(Response $response);
}