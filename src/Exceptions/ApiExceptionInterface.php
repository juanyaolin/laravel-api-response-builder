<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use Throwable;

interface ApiExceptionInterface extends Throwable
{
    /**
     * Gets the api code.
     */
    public function getApiCode(): int;

    /**
     * Gets the data.
     */
    public function getData(): mixed;
}
