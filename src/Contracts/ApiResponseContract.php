<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

use Symfony\Component\HttpFoundation\Response;

interface ApiResponseContract
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
    ): Response;

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
    ): Response;
}
