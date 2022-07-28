<?php

namespace Brezgalov\BaseApiClient;

use yii\base\InvalidConfigException;
use yii\httpclient\Request;

interface IApiClient
{
    /**
     * @param string $url
     * @return $this
     */
    public function setBaseUrl(string $url);

    /**
     * @param string $route
     * @param array $queryParams
     * @return string
     */
    public function buildRouteUrl(string $route, array $queryParams = []);

    /**
     * @param string $route
     * @param array $queryParams
     * @param array $input
     * @param Request|null $request
     * @return \yii\httpclient\Message|Request
     * @throws InvalidConfigException
     */
    public function prepareRequest(string $route, array $queryParams = [], Request $request = null);
}