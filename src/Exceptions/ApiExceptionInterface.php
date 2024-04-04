<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use Throwable;

interface ApiExceptionInterface extends Throwable
{
    /**
     * Gets the api code.
     */
    public function getApiCode(): int|string;

    /**
     * Gets the data.
     */
    public function getData(): mixed;

    /**
     * Get the additional data.
     */
    public function getAdditional(): ?array;
}
