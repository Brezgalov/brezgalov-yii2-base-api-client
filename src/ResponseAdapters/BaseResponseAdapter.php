<?php

namespace Brezgalov\BaseApiClient\ResponseAdapters;

use yii\httpclient\Request;
use yii\httpclient\Response;

/**
 * Class BaseResponseAdapter
 * @package Brezgalov\AuthServiceClient\ResponseAdapters
 */
abstract class BaseResponseAdapter implements IResponseAdapter
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $responseData;

    /**
     * BaseResponseAdapter constructor.
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;

        $this->scanResponse($response);
    }

    /**
     * @param Response $response
     * @return bool
     */
    protected function scanResponse(Response $response)
    {
        $this->response = $response;
        $this->responseData = $response->getData();

        if (!$this->getIsOk()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function getIsOk()
    {
        return $this->response->isOk;
    }

    /**
     * @return array
     */
    public function getRawData()
    {
        return $this->responseData;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}