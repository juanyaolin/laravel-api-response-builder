<?php

namespace Juanyaolin\ApiResponseBuilder\Adaptors;

use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Traits\HasExceptionToArrayConvertion;
use Throwable;
use UnitEnum;

class DefaultExceptionAdaptor implements ExceptionAdaptorContract
{
    use HasExceptionToArrayConvertion;

    public function __construct(protected Throwable $exception)
    {
    }

    public function apiCode(): int|string
    {
        return $this->apiCodeEnum()->apiCode();
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

    public function additional(): ?array
    {
        return null;
    }

    public function httpHeaders(): ?array
    {
        return null;
    }

    /**
     * The enum case for UncaughtException.
     */
    protected function apiCodeEnum(): ApiCodeContract|UnitEnum
    {
        return config(Constant::CONF_KEY_API_CODE_CLASS)::UncaughtException;
    }
}
