<?php

namespace Juanyaolin\ApiResponseBuilder\Adaptors;

use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Traits\HasExceptionToArrayConvertion;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use UnitEnum;

class HttpExceptionAdaptor implements ExceptionAdaptorContract
{
    use HasExceptionToArrayConvertion;

    public function __construct(protected HttpException $exception)
    {
    }

    public function apiCode(): int|string
    {
        return $this->apiCodeEnum()->apiCode();
    }

    public function statusCode(): int
    {
        return $this->exception->getStatusCode();
    }

    public function message(): string
    {
        if (!empty($message = $this->exception->getMessage())) {
            return $message;
        }

        return data_get(
            Response::$statusTexts,
            $this->statusCode(),
            $this->apiCodeEnum()->message()
        );
    }

    public function data(): mixed
    {
        return null;
    }

    public function debug(): ?array
    {
        return $this->convertExceptionToArray($this->exception);
    }

    public function additional(): ?array
    {
        return null;
    }

    public function httpHeaders(): ?array
    {
        return $this->exception->getHeaders();
    }

    /**
     * The enum case for HttpException.
     */
    protected function apiCodeEnum(): ApiCodeContract|UnitEnum
    {
        return config(Constant::CONF_KEY_API_CODE_CLASS)::HttpException;
    }
}
