<?php

namespace Juanyaolin\ApiResponseBuilder\Facades;

use Illuminate\Support\Facades\Facade;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstants as Constant;

/**
 * @method static \Symfony\Component\HttpFoundation\Response success(mixed $data = null, string $message = null, int $statusCode = null, int $apiCode = null, array $additional = null, array $httpHeader = null, int $jsonOptions = null)
 * @method static \Symfony\Component\HttpFoundation\Response error(int $apiCode = null, string $message = null, int $statusCode = null, array $debugData = null, mixed $data = null, array $additional = null, array $httpHeader = null, int $jsonOptions = null)
 *
 * @see \Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseContract
 */
class ApiResponse extends Facade
{
    protected static function getFacadeAccessor()
    {
        return config(Constant::CONF_KEY_RESPONSE_FACADE);
    }
}
