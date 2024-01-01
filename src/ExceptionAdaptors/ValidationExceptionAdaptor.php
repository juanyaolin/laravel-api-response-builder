<?php

namespace Juanyaolin\ApiResponseBuilder\ExceptionAdaptors;

use Illuminate\Validation\ValidationException;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstants as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Traits\HasExceptionToArrayConvertion;

class ValidationExceptionAdaptor implements ExceptionAdaptorContract
{
    use HasExceptionToArrayConvertion;

    public function __construct(protected ValidationException $exception)
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
        return $this->exception->getMessage();
    }

    public function data(): mixed
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

    /**
     * @return BackedEnum|\Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract
     */
    protected function apiCodeEnum()
    {
        return config(Constant::CONF_KEY_RENDERER_API_CODES)::ValidationException;
    }
}
