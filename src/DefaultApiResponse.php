<?php

namespace Juanyaolin\ApiResponseBuilder;

use Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseContract;
use Symfony\Component\HttpFoundation\Response;

class DefaultApiResponse implements ApiResponseContract
{
    /**
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
        return ApiResponseBuilder::asSuccess($apiCode)
            ->withMessage($message)
            ->withData($data)
            ->withStatusCode($statusCode)
            ->withAdditional($additional)
            ->withHttpHeaders($httpHeader)
            ->withJsonOptions($jsonOptions)
            ->build();
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
        return ApiResponseBuilder::asError($apiCode)
            ->withMessage($message)
            ->withData($data)
            ->withStatusCode($statusCode)
            ->withDebugData($debugData)
            ->withAdditional($additional)
            ->withHttpHeaders($httpHeader)
            ->withJsonOptions($jsonOptions)
            ->build();
    }
}
