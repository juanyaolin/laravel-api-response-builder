<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use Throwable;

interface ApiExceptionInterface extends Throwable
{
    /**
     * Gets the api code.
     *
     * @return int|string
     */
    public function getApiCode();

    /**
     * Gets the data.
     */
    public function getData();

    /**
     * Get the additional data.
     */
    public function getAdditional(): ?array;
}
