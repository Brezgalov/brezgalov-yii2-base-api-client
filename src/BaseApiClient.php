<?php

namespace Brezgalov\BaseApiClient;

use Brezgalov\BaseApiClient\Exception\RequestFailedException;
use Throwable;
use yii\base\Component;
use yii\httpclient\Client;
use yii\httpclient\Request;
use yii\httpclient\Response;

abstract class BaseApiClient extends Component implements IApiClient
{
    /**
     * @var string
     */
    public $baseUrl;

    /**
     * @var callable[]
     */
    private $onPrepareRequestCallbacks = [];

    /**
     * @var callable[]
     */
    private $onSendSucceedCallbacks = [];

    /**
     * @var callable[]
     */
    private $onSendFailedCallbacks = [];

    public function setBaseUrl(string $url): IApiClient
    {
        $this->baseUrl = $url;

        return $this;
    }

    public function addOnPrepareRequestCallback(callable $callback): BaseApiClient
    {
        $this->onPrepareRequestCallbacks[] = $callback;

        return $this;
    }

    public function addOnSendSucceedCallback(callable $callback): BaseApiClient
    {
        $this->onSendSucceedCallbacks[] = $callback;

        return $this;
    }

    public function addOnSendFailedCallback(callable $callback): BaseApiClient
    {
        $this->onSendFailedCallbacks[] = $callback;

        return $this;
    }

    public function clearEvents(): BaseApiClient
    {
        $this->onPrepareRequestCallbacks = [];
        $this->onSendSucceedCallbacks = [];
        $this->onSendFailedCallbacks = [];

        return $this;
    }

    public function buildRouteUrl(string $route, array $queryParams = []): string
    {
        $route = "{$this->baseUrl}/{$route}";

        if ($queryParams) {
            $route .= "?" . http_build_query($queryParams);
        }

        return $route;
    }

    public function prepareRequest(string $route, array $queryParams = [], Request $request = null): Request
    {
        if (empty($request)) {
            $request = $this->makeRequest();
        }

        $request = $request->setUrl($this->buildRouteUrl($route, $queryParams));

        $this->onPrepareRequest($request);

        return $request;
    }

    protected function makeRequest(): Request
    {
        return (new Client())->createRequest();
    }

    protected function onPrepareRequest(Request $request): void
    {
        foreach ($this->onPrepareRequestCallbacks as $callback) {
            call_user_func($callback, $request);
        }
    }

    public function sendRequest($request): Response
    {
        try {
            $response = $request->send();
            $this->onSendSuccess($request, $response);

            return $response;

        } catch (Throwable $ex) {

            $requestFailedException = new RequestFailedException($request, $ex->getCode(), $ex);
            $this->onSendFailed($requestFailedException);

            throw $requestFailedException;
        }
    }

    protected function onSendSuccess(Request $request, Response $response): void
    {
        foreach ($this->onSendSucceedCallbacks as $callback) {
            call_user_func($callback, $request, $response);
        }
    }

    protected function onSendFailed(RequestFailedException $requestFailedException): void
    {
        foreach ($this->onSendFailedCallbacks as $callback) {
            call_user_func($callback, $requestFailedException);
        }
    }
}
