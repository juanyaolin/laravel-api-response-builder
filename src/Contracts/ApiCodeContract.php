<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

interface ApiCodeContract
{
    /**
     * Value of ApiCode enumeration case.
     *
     * @return int|string
     */
    public function apiCode();

    /**
     * Http status code of enumeration case.
     */
    public function statusCode(): int;

    /**
     * Message of enumeration case.
     */
    public function message(): string;
}
