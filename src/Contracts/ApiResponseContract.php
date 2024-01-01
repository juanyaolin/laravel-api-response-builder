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
        string $message = null,
        int $statusCode = null,
        int $apiCode = null,
        array $additional = null,
        array $httpHeader = null,
        int $jsonOptions = null
    ): Response;

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
    ): Response;
}
