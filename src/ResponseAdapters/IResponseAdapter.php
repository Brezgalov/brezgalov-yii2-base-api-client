<?php

namespace Brezgalov\BaseApiClient\ResponseAdapters;

use yii\httpclient\Request;
use yii\httpclient\Response;

interface IResponseAdapter
{
    /**
     * @return bool
     */
    public function getIsOk();

    /**
     * @return array
     */
    public function getRawData();

    /**
     * @return Request
     */
    public function getRequest();

    /**
     * @return Response
     */
    public function getResponse();
}