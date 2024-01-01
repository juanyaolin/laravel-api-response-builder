<?php

namespace Juanyaolin\ApiResponseBuilder\ExceptionAdaptors;

use BackedEnum;
use Illuminate\Auth\AuthenticationException;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstants as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Traits\HasExceptionToArrayConvertion;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationExceptionAdaptor implements ExceptionAdaptorContract
{
    use HasExceptionToArrayConvertion;

    public function __construct(protected AuthenticationException $exception)
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

    public function httpHeaders(): ?array
    {
        return null;
    }

    /**
     * @return BackedEnum|\Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract
     */
    protected function apiCodeEnum()
    {
        return config(Constant::CONF_KEY_RENDERER_API_CODES)::AuthenticationException;
    }
}
