<?php

namespace Juanyaolin\ApiResponseBuilder\ApiCodes;

use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Symfony\Component\HttpFoundation\Response;

enum DefaultApiCodeEnum: int implements ApiCodeContract
{
    case Success = 0;
    case Error = -1;
    case UncaughtException = -2;
    case ValidationException = -3;
    case AuthenticationException = -4;
    case HttpException = -5;
    case ApiException = -6;

    public function apiCode(): int|string
    {
        return $this->value;
    }

    public function statusCode(): int
    {
        return match ($this) {
            self::Success => Response::HTTP_OK,
            self::UncaughtException => Response::HTTP_INTERNAL_SERVER_ERROR,
            self::ValidationException => Response::HTTP_UNPROCESSABLE_ENTITY,
            self::AuthenticationException => Response::HTTP_UNAUTHORIZED,

            default => Response::HTTP_BAD_REQUEST,
        };
    }

    public function message(): string
    {
        return match ($this) {
            self::Success => 'Success',
            self::UncaughtException => 'Server Error',
            self::ValidationException => 'Unprocessable Content',
            self::AuthenticationException => 'Unauthenticated',
            self::HttpException => 'Bad Request',
            self::ApiException => "Error [{$this->value}]",

            default => 'Error',
        };
    }
}
