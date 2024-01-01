<?php

namespace Juanyaolin\ApiResponseBuilder\Traits;

use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;
use Symfony\Component\HttpFoundation\Response;

trait HasApiResponseMethods
{
    /*
     * Make a success response.
     */
    public function success(
        mixed $data = null,
        string $message = null,
        int $statusCode = null,
        int $apiCode = null,
        array $additional = null,
        array $httpHeader = null,
        int $jsonOptions = null
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

    /*
     * Make a error response.
     */
    public function error(
        int $apiCode = null,
        string $message = null,
        int $statusCode = null,
        array $debugData = null,
        mixed $data = null,
        array $additional = null,
        array $httpHeader = null,
        int $jsonOptions = null
    ): Response {
        return ApiResponse::error(
            $apiCode,
            $message,
            $statusCode,
            $debugData,
            $data,
            $additional,
            $httpHeader,
            $jsonOptions,
        );
    }
}
