<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use InvalidArgumentException;

class ShouldBeSubclassOfContractException extends InvalidArgumentException
{
    public function __construct(string $class, string $contract)
    {
        parent::__construct("Class [{$class}] should implement interface [{$contract}]");
    }
}
