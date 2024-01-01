<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use BackedEnum;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstants as Constant;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ApiException extends RuntimeException implements ApiExceptionInterface, HttpExceptionInterface
{
    protected mixed $data = null;

    public function __construct(
        string $message = null,
        int $code = 0,
        Throwable $previous = null,
        mixed $data = null
    ) {
        parent::__construct(
            $message ?? $this->apiCodeEnum()->message(),
            $code,
            $previous
        );

        $this->data = $data;
    }

    public function getApiCode(): int
    {
        return $this->apiCodeEnum()->value;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getStatusCode(): int
    {
        return $this->apiCodeEnum()->statusCode();
    }

    public function getHeaders(): array
    {
        return [];
    }

    /**
     * @return BackedEnum|\Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract
     */
    protected function apiCodeEnum()
    {
        return config(Constant::CONF_KEY_RENDERER_API_CODES)::ApiException;
    }
}
