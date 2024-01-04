<?php

namespace Juanyaolin\ApiResponseBuilder\Adaptors;

use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Traits\HasExceptionToArrayConvertion;
use MyCLabs\Enum\Enum;
use Throwable;
use UnitEnum;

class DefaultExceptionAdaptor implements ExceptionAdaptorContract
{
    use HasExceptionToArrayConvertion;

    protected Throwable $exception;

    public function __construct(Throwable $exception)
    {
        $this->exception = $exception;
    }

    public function apiCode()
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

    public function data()
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
     * @return ApiCodeContract|Enum|UnitEnum
     */
    protected function apiCodeEnum()
    {
        $apiCodeClass = config(Constant::CONF_KEY_API_CODE_CLASS);

        return is_subclass_of($apiCodeClass, Enum::class)
            ? $apiCodeClass::UncaughtException()
            : $apiCodeClass::UncaughtException;
    }
}
