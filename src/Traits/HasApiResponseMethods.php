<?php

namespace Juanyaolin\ApiResponseBuilder\Traits;

use Illuminate\Http\Request;
use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

trait HasApiResponseMethods
{
    /*
     * Make a success response.
     */
    public function success(
        mixed $data = null,
        string $message = null,
        int $httpCode = null,
        int $apiCode = null,
        array $httpHeader = null,
        int $jsonOptions = null
    ): JsonResponse {
        return ApiResponse::success(
            $data,
            $message,
            $httpCode,
            $apiCode,
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
        int $httpCode = null,
        array $debugData = null,
        mixed $data = null,
        array $httpHeader = null,
        int $jsonOptions = null
    ): JsonResponse {
        return ApiResponse::error(
            $apiCode,
            $message,
            $httpCode,
            $debugData,
            $data,
            $httpHeader,
            $jsonOptions,
        );
    }

    /**
     * An renderer for Exception Handler.
     */
    public function exceptionRenderer(
        \Throwable $throwable,
        Request $request
    ): JsonResponse {
        return ApiResponse::exceptionRenderer($throwable, $request);
    }
}
