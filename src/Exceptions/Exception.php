<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use Juanyaolin\ApiResponseBuilder\BasicApiCodes;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

abstract class Exception extends \Exception implements ExceptionInterface, HttpExceptionInterface
{
    private mixed $data = null;

    public function getApiCode(): int
    {
        return BasicApiCodes::UNCAUGHT_EXCEPTION;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    public function getHeaders(): array
    {
        return [];
    }
}
