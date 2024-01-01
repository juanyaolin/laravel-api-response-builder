<?php

namespace Juanyaolin\ApiResponseBuilder\Traits;

use Illuminate\Support\Arr;
use Throwable;

trait HasExceptionToArrayConvertion
{
    /**
     * Convert the given exception to an array.
     */
    protected function convertExceptionToArray(Throwable $exception): ?array
    {
        if (!config('app.debug')) {
            return null;
        }

        return [
            'exception' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => collect($exception->getTrace())
                ->map(fn ($trace) => Arr::except($trace, ['args']))
                ->all(),
        ];
    }
}
