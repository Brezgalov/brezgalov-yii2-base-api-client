<?php

namespace Brezgalov\BaseApiClient;

use Brezgalov\BaseApiClient\Exceptions\RequestFailedException;
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
     * signature: function (Request $request): void
     * @var callable[]
     */
    private $onPrepareRequestCallbacks = [];

    /**
     * signature: function (Request $request): void
     * @var callable[]
     */
    private $onBeforeSendCallbacks = [];

    /**
     * signature: function (Request $request, Response $response): void
     * @var callable[]
     */
    private $onSendSucceedCallbacks = [];

    /**
     * signature: function (Throwable $ex, Request $request): void
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

    public function addOnBeforeSendCallback(callable $callback): BaseApiClient
    {
        $this->onBeforeSendCallbacks[] = $callback;

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

    public function sendRequest(Request $request): Response
    {
        try {
            $this->onBeforeSend($request);

            $response = $request->send();

            $this->onSendSuccess($request, $response);

            return $response;

        } catch (Throwable $ex) {

            $this->onSendFailed($ex, $request);

            throw $ex;
        }
    }

    protected function onBeforeSend(Request $request): void
    {
        foreach ($this->onBeforeSendCallbacks as $callback) {
            call_user_func($callback, $request);
        }
    }

    protected function onSendSuccess(Request $request, Response $response): void
    {
        foreach ($this->onSendSucceedCallbacks as $callback) {
            call_user_func($callback, $request, $response);
        }
    }

    protected function onSendFailed(Throwable $ex, Request $request): void
    {
        foreach ($this->onSendFailedCallbacks as $callback) {
            call_user_func($callback, $ex, $request);
        }
    }
}
