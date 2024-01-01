<?php

namespace Juanyaolin\ApiResponseBuilder;

use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Symfony\Component\HttpFoundation\Response;

enum DefaultApiCode: int implements ApiCodeContract
{
    case Success = 0;
    case UncaughtException = -1;
    case ValidationException = -2;
    case AuthenticationException = -3;
    case HttpException = -4;
    case ApiException = -5;

    public function statusCode(): int
    {
        return match ($this) {
            self::Success => Response::HTTP_OK,
            self::ValidationException => Response::HTTP_UNPROCESSABLE_ENTITY,
            self::AuthenticationException => Response::HTTP_UNAUTHORIZED,
            self::HttpException => Response::HTTP_BAD_REQUEST,
            self::ApiException => Response::HTTP_BAD_REQUEST,
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };
    }

    public function message(): string
    {
        return match ($this) {
            self::Success => 'Success',
            self::ValidationException => 'Unprocessable Content',
            self::AuthenticationException => 'Unauthenticated',
            self::HttpException => 'Bad Request',
            self::ApiException => "Error [{$this->value}]",
            default => 'Server Error',
        };
    }
}
