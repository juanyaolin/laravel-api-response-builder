<?php

namespace Juanyaolin\ApiResponseBuilder\Adaptors;

use Illuminate\Validation\ValidationException;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Traits\HasExceptionToArrayConvertion;
use MyCLabs\Enum\Enum;
use UnitEnum;

class ValidationExceptionAdaptor implements ExceptionAdaptorContract
{
    use HasExceptionToArrayConvertion;

    protected ValidationException $exception;

    public function __construct(ValidationException $exception)
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
        return $this->exception->getMessage();
    }

    public function data()
    {
        return $this->exception->errors();
    }

    public function debug(): ?array
    {
        return null;
    }

    public function httpHeaders(): ?array
    {
        return null;
    }

    public function additional(): ?array
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
            ? $apiCodeClass::ValidationException()
            : $apiCodeClass::ValidationException;
    }
}
