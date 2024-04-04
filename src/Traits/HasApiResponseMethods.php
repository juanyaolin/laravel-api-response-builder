<?php

namespace Juanyaolin\ApiResponseBuilder\Traits;

use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

trait HasApiResponseMethods
{
    /**
     * Make a success response.
     */
    public function success(
        mixed $data = null,
        ?string $message = null,
        ?int $statusCode = null,
        int|string|null $apiCode = null,
        ?array $additional = null,
        ?array $httpHeader = null,
        ?int $jsonOptions = null
    ): Response {
        return ApiResponse::success(
            $data,
            $message,
            $statusCode,
            $apiCode,
            $additional,
            $httpHeader,
            $jsonOptions
        );
    }

    /**
     * Make a error response.
     */
    public function error(
        ?string $message = null,
        mixed $data = null,
        ?int $statusCode = null,
        int|string|null $apiCode = null,
        ?array $debugData = null,
        ?array $additional = null,
        ?array $httpHeader = null,
        ?int $jsonOptions = null
    ): Response {
        return ApiResponse::error(
            $message,
            $data,
            $statusCode,
            $apiCode,
            $debugData,
            $additional,
            $httpHeader,
            $jsonOptions
        );
    }
}
