<?php

namespace Brezgalov\BaseApiClient\DataFormatters;

use yii\httpclient\Response;

/**
 * Class ModelErrorsDataFormatter
 * @package Brezgalov\AuthServiceClient\DataFormatters
 */
class ModelErrorsDataFormatter implements IDataFormatter
{
    public static function formatArray(array $response)
    {
        $res = [];
        foreach ($response as $errorItem) {
            $res[$errorItem['field']][] = $errorItem['message'];
        }

        return $res;
    }

    /**
     * @param Response $response
     * @return array
     */
    public static function format(Response $response)
    {
        return self::formatArray(
            $response->getData()
        );
    }
}