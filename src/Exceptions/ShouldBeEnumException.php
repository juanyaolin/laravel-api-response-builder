<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use InvalidArgumentException;
use MyCLabs\Enum\Enum;

class ShouldBeEnumException extends InvalidArgumentException
{
    public function __construct(string $class, string $abstract = Enum::class)
    {
        parent::__construct("Class [{$class}] should be subclass of [{$abstract}] or an enum(>= PHP8.1)");
    }
}
