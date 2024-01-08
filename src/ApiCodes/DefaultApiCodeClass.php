<?php

namespace Juanyaolin\ApiResponseBuilder\ApiCodes;

use Symfony\Component\HttpFoundation\Response;

final class DefaultApiCodeClass extends BasicApiCodeClass
{
    public const Success = 0;
    public const Error = -1;
    public const UncaughtException = -2;
    public const ValidationException = -3;
    public const AuthenticationException = -4;
    public const HttpException = -5;
    public const ApiException = -6;

    protected function statusCodeMapping(): array
    {
        return [
            static::Success => Response::HTTP_OK,
            static::UncaughtException => Response::HTTP_INTERNAL_SERVER_ERROR,
            static::ValidationException => Response::HTTP_UNPROCESSABLE_ENTITY,
            static::AuthenticationException => Response::HTTP_UNAUTHORIZED,
        ];
    }

    protected function messageMapping(): array
    {
        return [
            static::Success => 'Success',
            static::UncaughtException => 'Server Error',
            static::ValidationException => 'Unprocessable Content',
            static::AuthenticationException => 'Unauthenticated',
            static::HttpException => 'Bad Request',
            static::ApiException => "Error [{$this->apiCode()}]",
        ];
    }

    protected function defaultStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    protected function defaultMessage(): string
    {
        return 'Error';
    }
}
