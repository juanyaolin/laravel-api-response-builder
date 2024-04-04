<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

interface ExceptionAdaptorContract
{
    /**
     * Api code of of exception or throwable.
     */
    public function apiCode(): int|string;

    /**
     * Http status code of exception or throwable.
     */
    public function statusCode(): int;

    /**
     * Message of exception or throwable.
     */
    public function message(): string;

    /**
     * Data of exception or throwable.
     */
    public function data(): mixed;

    /**
     * Debug information of exception or throwable.
     */
    public function debug(): ?array;

    /**
     * Additional data of exception or throwable.
     */
    public function additional(): ?array;

    /**
     * Http headers of of exception or throwable.
     */
    public function httpHeaders(): ?array;
}
