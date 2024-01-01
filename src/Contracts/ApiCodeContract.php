<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

interface ApiCodeContract
{
    /**
     * Message of enumeration case.
     */
    public function message(): string;

    /**
     * Http status code of enumeration case.
     */
    public function statusCode(): int;
}
