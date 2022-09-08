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
     * @var callable
     */
    public $prepareRequestCallback;

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
        $route = "{$this->baseUrl}/{$route}";

        if ($queryParams) {
            $route .= "?" . http_build_query($queryParams);
        }

        return $route;
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

        $request = $request->setUrl($this->buildRouteUrl($route, $queryParams));

        if (is_callable($this->prepareRequestCallback)) {
            $request = call_user_func($this->prepareRequestCallback, $request);
        }

        return $request;
    }
}