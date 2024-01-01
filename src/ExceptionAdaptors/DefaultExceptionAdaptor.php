<?php

namespace Juanyaolin\ApiResponseBuilder\ExceptionAdaptors;

use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstants as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Traits\HasExceptionToArrayConvertion;
use Throwable;

class DefaultExceptionAdaptor implements ExceptionAdaptorContract
{
    use HasExceptionToArrayConvertion;

    public function __construct(protected Throwable $exception)
    {
    }

    public function apiCode(): int
    {
        return $this->apiCodeEnum()->value;
    }

    public function statusCode(): int
    {
        return $this->apiCodeEnum()->statusCode();
    }

    public function message(): string
    {
        return config('app.debug')
            ? $this->exception->getMessage()
            : $this->apiCodeEnum()->message();
    }

    public function data(): mixed
    {
        return null;
    }

    public function debug(): ?array
    {
        return $this->convertExceptionToArray($this->exception);
    }

    public function httpHeaders(): ?array
    {
        return null;
    }

    /**
     * @return BackedEnum|\Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract
     */
    protected function apiCodeEnum()
    {
        return config(Constant::CONF_KEY_RENDERER_API_CODES)::UncaughtException;
    }
}
