<?php

namespace Juanyaolin\ApiResponseBuilder\Facades;

use Illuminate\Support\Facades\Facade;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;

/**
 * @method static \Symfony\Component\HttpFoundation\Response success($data = null, string|null $message = null, int|null $statusCode = null, int|string|null $apiCode = null, array|null $additional = null, array|null $httpHeader = null, int|null $jsonOptions = null)
 * @method static \Symfony\Component\HttpFoundation\Response error(string|null $message = null, $data = null, int|null $statusCode = null, int|string|null $apiCode = null, array|null $debugData = null, array|null $additional = null, array|null $httpHeader = null, int|null $jsonOptions = null)
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
