<?php

namespace Juanyaolin\ApiResponseBuilder\Adaptors;

use Illuminate\Validation\ValidationException;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Traits\HasExceptionToArrayConvertion;
use UnitEnum;

class ValidationExceptionAdaptor implements ExceptionAdaptorContract
{
    use HasExceptionToArrayConvertion;

    public function __construct(protected ValidationException $exception)
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
     * The enum case for ValidationException.
     */
    protected function apiCodeEnum(): ApiCodeContract|UnitEnum
    {
        return config(Constant::CONF_KEY_API_CODE_CLASS)::ValidationException;
    }
}
