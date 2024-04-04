<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

interface ApiCodeContract
{
    /**
     * Value of ApiCode enumeration case.
     */
    public function apiCode(): int|string;

    /**
     * Http status code of enumeration case.
     */
    public function statusCode(): int;

    /**
     * Message of enumeration case.
     */
    public function message(): string;
}
