<?php

namespace Juanyaolin\ApiResponseBuilder\ExceptionAdaptors;

use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Exceptions\ApiException;
use Juanyaolin\ApiResponseBuilder\Traits\HasExceptionToArrayConvertion;

class ApiExceptionAdaptor implements ExceptionAdaptorContract
{
    use HasExceptionToArrayConvertion;

    public function __construct(protected ApiException $exception)
    {
    }

    public function apiCode(): int
    {
        return $this->exception->getApiCode();
    }

    public function statusCode(): int
    {
        return $this->exception->getStatusCode();
    }

    public function message(): string
    {
        return $this->exception->getMessage();
    }

    public function data(): mixed
    {
        return $this->exception->getData();
    }

    public function debug(): ?array
    {
        return $this->convertExceptionToArray($this->exception);
    }

    public function httpHeaders(): ?array
    {
        return $this->exception->getHeaders();
    }
}
