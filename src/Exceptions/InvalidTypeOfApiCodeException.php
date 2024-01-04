<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use InvalidArgumentException;

class InvalidTypeOfApiCodeException extends InvalidArgumentException
{
    public function __construct(string $type)
    {
        parent::__construct("Argument apiCode must be of the type int or string or null, but {$type} given");
    }
}
