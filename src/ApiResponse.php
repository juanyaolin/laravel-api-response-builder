<?php

namespace Juanyaolin\ApiResponseBuilder;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseInterface;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ApiResponse implements ApiResponseInterface
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
    ): JsonResponse {
        return ApiResponseBuilder::asSuccess($apiCode)
            ->withMessage($message)
            ->withData($data)
            ->withHttpCode($httpCode)
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
        int $httpCode = null,
        array $debugData = null,
        mixed $data = null,
        array $httpHeader = null,
        int $jsonOptions = null
    ): JsonResponse {
        return ApiResponseBuilder::asError($apiCode)
            ->withMessage($message)
            ->withData($data)
            ->withHttpCode($httpCode)
            ->withDebugData($debugData)
            ->withHttpHeaders($httpHeader)
            ->withJsonOptions($jsonOptions)
            ->build();
    }

    /**
     * An renderer for exception handler.
     */
    public function exceptionRenderer(
        Throwable $throwable,
        Request $request
    ): JsonResponse {
        // ApiCode and Data
        if ($this->isCustomizeException($throwable)) {
            /** @var ExceptionInterface $throwable */
            $apiCode = $throwable->getApiCode();
            $data = $throwable->getData();
        } else {
            $apiCode = BasicApiCodes::UNCAUGHT_EXCEPTION;
            $data = null;
        }

        // Http Status Code
        if ($this->isHttpException($throwable)) {
            /** @var HttpExceptionInterface $throwable */
            $httpCode = $throwable->getStatusCode();
            $httpHeader = $throwable->getHeaders();
        } else {
            $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $httpHeader = null;
        }

        return $this->error(
            $apiCode,
            $throwable->getMessage(),
            $httpCode,
            [
                'exception' => get_class($throwable),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => collect($throwable->getTrace())
                    ->map(fn ($trace) => Arr::except($trace, ['args']))
                    ->all(),
            ],
            $data,
            $httpHeader
        );
    }

    private function isHttpException(Throwable $throwable): bool
    {
        return $throwable instanceof HttpExceptionInterface;
    }

    private function isCustomizeException(Throwable $throwable): bool
    {
        return $throwable instanceof ExceptionInterface;
    }
}
