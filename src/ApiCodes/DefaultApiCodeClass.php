<?php

namespace Juanyaolin\ApiResponseBuilder\ApiCodes;

use Symfony\Component\HttpFoundation\Response;

final class DefaultApiCodeClass extends BasicApiCodeClass
{
    public const Success = 0;
    public const UncaughtException = -1;
    public const ValidationException = -2;
    public const AuthenticationException = -3;
    public const HttpException = -4;
    public const ApiException = -5;

    protected function statusCodeMapping(): array
    {
        return [
            static::Success => Response::HTTP_OK,
            static::UncaughtException => $this->defaultStatusCode(),
            static::ValidationException => Response::HTTP_UNPROCESSABLE_ENTITY,
            static::AuthenticationException => Response::HTTP_UNAUTHORIZED,
            static::HttpException => Response::HTTP_BAD_REQUEST,
            static::ApiException => Response::HTTP_BAD_REQUEST,
        ];
    }

    protected function messageMapping(): array
    {
        return [
            static::Success => 'Success',
            static::UncaughtException => $this->defaultMessage(),
            static::ValidationException => 'Unprocessable Content',
            static::AuthenticationException => 'Unauthenticated',
            static::HttpException => 'Bad Request',
            static::ApiException => "Error [{$this->value}]",
        ];
    }

    protected function defaultStatusCode(): int
    {
        return Response::HTTP_INTERNAL_SERVER_ERROR;
    }

    protected function defaultMessage(): string
    {
        return 'Server Error';
    }
}
