<?php

namespace Juanyaolin\ApiResponseBuilder\Renderers;

use Illuminate\Http\Request;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionRendererContract;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LaravelRenderer implements ExceptionRendererContract
{
    public function render(Throwable $throwable, Request $request): ?Response
    {
        // To cancel processing of renderable() in exception handler
        return null;
    }
}
