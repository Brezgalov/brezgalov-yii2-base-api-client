<?php

namespace Brezgalov\BaseApiClient\DataFormatters;

use yii\httpclient\Response;

/**
 * Class ModelErrorsDataFormatter
 * @package Brezgalov\AuthServiceClient\DataFormatters
 */
class ModelErrorsDataFormatter implements IDataFormatter
{
    /**
     * @param Response $response
     * @return array
     */
    public static function format(Response $response)
    {
        $data = $response->getData();

        $res = [];
        foreach ($data as $datum) {
            $res[$datum['field']][] = $datum['message'];
        }

        return $res;
    }
}