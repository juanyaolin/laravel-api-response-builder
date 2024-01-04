<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use MyCLabs\Enum\Enum;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;
use UnitEnum;

class ApiException extends RuntimeException implements ApiExceptionInterface, HttpExceptionInterface
{
    /**
     * The data.
     */
    protected $data;

    /**
     * The additional data.
     */
    protected $additional;

    public function __construct(
        string $message = null,
        int $code = 0,
        Throwable $previous = null,
        $data = null,
        $additional = null
    ) {
        parent::__construct(
            $message ?? $this->apiCodeEnum()->message(),
            $code,
            $previous
        );

        $this->data = $data;
        $this->additional = $additional;
    }

    public function getApiCode()
    {
        return $this->apiCodeEnum()->apiCode();
    }

    public function getStatusCode(): int
    {
        return $this->apiCodeEnum()->statusCode();
    }

    public function getData()
    {
        return $this->data;
    }

    public function getAdditional(): ?array
    {
        return $this->additional;
    }

    public function getHeaders(): array
    {
        return [];
    }

    /**
     * The enum case for ApiException.
     *
     * @return ApiCodeContract|Enum|UnitEnum
     */
    protected function apiCodeEnum()
    {
        $apiCodeClass = config(Constant::CONF_KEY_API_CODE_CLASS);

        return is_subclass_of($apiCodeClass, Enum::class)
            ? $apiCodeClass::ApiException()
            : $apiCodeClass::ApiException;
    }
}
