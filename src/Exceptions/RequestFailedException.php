<?php

namespace Brezgalov\BaseApiClient\Exception;

use Throwable;
use yii\httpclient\Request;

/**
 * Class RequestFailedException
 * @package Brezgalov\BaseApiClient\Exception
 *
 * Используется в пакетах "наследниках", если отправка запроса выбрасывает Exception.
 * Позволяет залогировать запрос при котором произошел Exception.
 */
class RequestFailedException extends \Exception
{
    /**
     * @var Request
     */
    private $request;

    /**
     * RequestFailedException constructor.
     * @param Request $request
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(Request $request, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->request = $request;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }
}