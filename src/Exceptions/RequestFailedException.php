<?php

namespace Brezgalov\BaseApiClient\Exceptions;

use Throwable;
use yii\httpclient\Request;

class RequestFailedException extends \Exception
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->request = $request;

        parent::__construct($message, $code, $previous);
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
