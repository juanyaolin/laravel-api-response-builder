<?php

namespace Juanyaolin\ApiResponseBuilder\Adaptors;

use Illuminate\Auth\AuthenticationException;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Traits\HasExceptionToArrayConvertion;
use MyCLabs\Enum\Enum;
use Symfony\Component\HttpFoundation\Response;
use UnitEnum;

class AuthenticationExceptionAdaptor implements ExceptionAdaptorContract
{
    use HasExceptionToArrayConvertion;

    protected AuthenticationException $exception;

    public function __construct(AuthenticationException $exception)
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
        if (!empty($message = $this->exception->getMessage())) {
            return $message;
        }

        return data_get(
            Response::$statusTexts,
            $this->statusCode(),
            $this->apiCodeEnum()->message()
        );
    }

    public function data()
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
     * @return ApiCodeContract|Enum|UnitEnum
     */
    protected function apiCodeEnum()
    {
        $apiCodeClass = config(Constant::CONF_KEY_API_CODE_CLASS);

        return is_subclass_of($apiCodeClass, Enum::class)
            ? $apiCodeClass::AuthenticationException()
            : $apiCodeClass::AuthenticationException;
    }
}
