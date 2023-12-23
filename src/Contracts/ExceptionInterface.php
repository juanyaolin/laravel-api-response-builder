<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

interface ExceptionInterface extends \Throwable
{
    /** Get ApiCode of exception */
    public function getApiCode(): int;

    /** Get Exception Data */
    public function getData(): mixed;
}
