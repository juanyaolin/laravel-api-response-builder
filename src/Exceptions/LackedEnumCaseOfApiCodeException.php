<?php

namespace Juanyaolin\ApiResponseBuilder\Exceptions;

use InvalidArgumentException;

class LackedEnumCaseOfApiCodeException extends InvalidArgumentException
{
    public function __construct(string $class, $lacked)
    {
        if (is_array($lacked)) {
            $lacked = implode(', ', $lacked);
        }

        parent::__construct("Class [{$class}] should contain following cases: {$lacked}");
    }
}
