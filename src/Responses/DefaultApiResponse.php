<?php

namespace Juanyaolin\ApiResponseBuilder\Responses;

use Juanyaolin\ApiResponseBuilder\ApiResponseBuilder;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseContract;
use Symfony\Component\HttpFoundation\Response;

class DefaultApiResponse implements ApiResponseContract
{
    public function success(
        mixed $data = null,
        ?string $message = null,
        ?int $statusCode = null,
        int|string|null $apiCode = null,
        ?array $additional = null,
        ?array $httpHeader = null,
        ?int $jsonOptions = null
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
