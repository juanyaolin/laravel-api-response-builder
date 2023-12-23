<?php

namespace Juanyaolin\ApiResponseBuilder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Symfony\Component\HttpFoundation\JsonResponse success(mixed $data = null, string $message = null, int $httpCode = null, int $apiCode = null, array $httpHeader = null, int $jsonOptions = null)
 * @method static \Symfony\Component\HttpFoundation\JsonResponse error(int $apiCode = null, string $message = null, int $httpCode = null, array $debugData = null, mixed $data = null, array $httpHeader = null, int $jsonOptions = null)
 * @method static \Symfony\Component\HttpFoundation\JsonResponse exceptionRenderer(\Throwable $throwable, \Illuminate\Http\Request $request)
 *
 * @see \Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseInterface
 */
class ApiResponse extends Facade
{
    protected static function getFacadeAccessor()
    {
        return config('api-response-builder.response.facade');
    }
}
