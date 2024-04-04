<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use InvalidArgumentException;
use UnitEnum;

class ShouldBeEnumException extends InvalidArgumentException
{
    public function __construct(string $class, string $abstract = UnitEnum::class)
    {
        parent::__construct("Class [{$class}] should be subclass of [{$abstract}].");
    }
}
