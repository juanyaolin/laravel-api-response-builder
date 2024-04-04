<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;
use UnitEnum;

class ApiException extends RuntimeException implements ApiExceptionInterface, HttpExceptionInterface
{
    /** The data. */
    protected $data;

    /** The additional data. */
    protected $additional;

    public function __construct(
        ?string $message = null,
        int $code = 0,
        ?Throwable $previous = null,
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

    public function getApiCode(): int|string
    {
        return $this->apiCodeEnum()->apiCode();
    }

    public function getStatusCode(): int
    {
        return $this->apiCodeEnum()->statusCode();
    }

    public function getData(): mixed
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
     */
    protected function apiCodeEnum(): ApiCodeContract|UnitEnum
    {
        return config(Constant::CONF_KEY_API_CODE_CLASS)::ApiException;
    }
}
