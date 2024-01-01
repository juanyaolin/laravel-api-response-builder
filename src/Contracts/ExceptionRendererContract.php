<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

interface ExceptionRendererContract
{
    public function render(Throwable $throwable, Request $request): ?Response;
}
