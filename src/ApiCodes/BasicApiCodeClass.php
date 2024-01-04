<?php

namespace Juanyaolin\ApiResponseBuilder\ApiCodes;

use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use MyCLabs\Enum\Enum;

abstract class BasicApiCodeClass extends Enum implements ApiCodeContract
{
    public function apiCode()
    {
        return $this->getValue();
    }

    public function statusCode(): int
    {
        return array_key_exists($this->apiCode(), $this->statusCodeMapping())
            ? $this->statusCodeMapping()[$this->apiCode()]
            : $this->defaultStatusCode();
    }

    public function message(): string
    {
        return array_key_exists($this->apiCode(), $this->messageMapping())
            ? $this->messageMapping()[$this->apiCode()]
            : $this->defaultMessage();
    }

    /**
     * The mapping of the enum value to the HTTP status code.
     */
    abstract protected function statusCodeMapping(): array;

    /**
     * The mapping of the enum value to the message.
     */
    abstract protected function messageMapping(): array;

    /**
     * The default HTTP status code.
     */
    abstract protected function defaultStatusCode(): int;

    /**
     * The default message.
     */
    abstract protected function defaultMessage(): string;
}
