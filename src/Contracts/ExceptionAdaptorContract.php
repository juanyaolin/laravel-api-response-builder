<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

interface ExceptionAdaptorContract
{
    /**
     * Api code of of exception or throwable.
     *
     * @return int|string
     */
    public function apiCode();

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
    public function data();

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
