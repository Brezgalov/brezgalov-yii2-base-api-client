<?php

namespace Brezgalov\BaseApiClient;

use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Request;

abstract class BaseApiClient extends Component implements IApiClient
{
    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @param string $url
     * @return $this
     */
    public function setBaseUrl(string $url)
    {
        $this->baseUrl = $url;

        return $this;
    }

    /**
     * @param string $route
     * @param array $queryParams
     * @return string
     */
    public function buildRouteUrl(string $route, array $queryParams = [])
    {
        return "{$this->baseUrl}/{$route}?" . http_build_query($queryParams);
    }

    /**
     * @param string $route
     * @param array $queryParams
     * @param array $input
     * @param Request|null $request
     * @return \yii\httpclient\Message|Request
     * @throws InvalidConfigException
     */
    public function prepareRequest(string $route, array $queryParams = [], Request $request = null)
    {
        if (empty($request)) {
            $request = (new Client())->createRequest();
        }

        return $request->setUrl($this->buildRouteUrl($route, $queryParams));
    }
}