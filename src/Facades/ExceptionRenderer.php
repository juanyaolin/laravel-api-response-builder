<?php

namespace Juanyaolin\ApiResponseBuilder\Facades;

use Illuminate\Support\Facades\Facade;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstants as Constant;

/**
 * @method static \Symfony\Component\HttpFoundation\Response render(\Throwable $throwable, \Illuminate\Http\Request $request)
 *
 * @see \Juanyaolin\ApiResponseBuilder\Contracts\ExceptionRendererContract
 */
class ExceptionRenderer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return config(Constant::CONF_KEY_RENDERER_FACADE);
    }
}
