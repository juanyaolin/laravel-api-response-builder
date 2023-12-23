<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

interface ApiResponseInterface
{
    /**
     * Make a success response.
     */
    public function success(
        mixed $data = null,
        string $message = null,
        int $httpCode = null,
        int $apiCode = null,
        array $httpHeader = null,
        int $jsonOptions = null
    ): JsonResponse;

    /*
     * Make a error response.
     */
    public function error(
        int $apiCode = null,
        string $message = null,
        int $httpCode = null,
        array $debugData = null,
        mixed $data = null,
        array $httpHeader = null,
        int $jsonOptions = null
    ): JsonResponse;

    /**
     * An renderer for exception handler.
     */
    public function exceptionRenderer(
        \Throwable $throwable,
        Request $request
    ): JsonResponse;
}
