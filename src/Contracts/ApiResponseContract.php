<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

use Symfony\Component\HttpFoundation\Response;

interface ApiResponseContract
{
    /**
     * Make a success response.
     *
     * @param int|string|null $apiCode
     */
    public function success(
        $data = null,
        string $message = null,
        int $statusCode = null,
        $apiCode = null,
        array $additional = null,
        array $httpHeader = null,
        int $jsonOptions = null
    ): Response;

    /**
     * Make a error response.
     *
     * @param int|string|null $apiCode
     */
    public function error(
        $apiCode = null,
        string $message = null,
        int $statusCode = null,
        array $debugData = null,
        $data = null,
        array $additional = null,
        array $httpHeader = null,
        int $jsonOptions = null
    ): Response;
}
