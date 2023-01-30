<?php

namespace Brezgalov\BaseApiClient;

use yii\httpclient\Request;
use yii\httpclient\Response;

interface IApiClient
{
    public function setBaseUrl(string $url): IApiClient;

    public function buildRouteUrl(string $route, array $queryParams = []): string;

    public function prepareRequest(string $route, array $queryParams = [], Request $request = null): Request;

    public function sendRequest(Request $request): Response;
}
