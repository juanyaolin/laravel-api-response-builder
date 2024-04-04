<?php

namespace Juanyaolin\ApiResponseBuilder\Adaptors;

use Illuminate\Auth\AuthenticationException;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Traits\HasExceptionToArrayConvertion;
use Symfony\Component\HttpFoundation\Response;
use UnitEnum;

class AuthenticationExceptionAdaptor implements ExceptionAdaptorContract
{
    use HasExceptionToArrayConvertion;

    public function __construct(protected AuthenticationException $exception)
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
        return null;
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
     * The enum case for AuthenticationException.
     */
    protected function apiCodeEnum(): ApiCodeContract|UnitEnum
    {
        return config(Constant::CONF_KEY_API_CODE_CLASS)::AuthenticationException;
    }
}
