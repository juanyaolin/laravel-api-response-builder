# Exception Handling

This chapter will cover how to enable exception rendering, how to use the `ApiException` exception class, and provide examples for customizing ApiCode.

[&laquo; Documentation](./documents.md)

* [Exception Renderer](#exception-renderer)
* [Exceptions](#exceptions)
* [ApiCode](#apicode)

## Exception Renderer

If you are already using `ApiResponse::success()` or `ApiResponse::error()` to create response, it is highly recommended to render exception by `ExceptionRenderer` to ensure responses from your Laravel project respond the same structure. Because clients would not hope to receive non-JSON or differently structured responses leading to unexpected errors.

To enable the exception renderer, calling `ExceptionRenderer::render()` in `register()` of ***App\Exceptions\Handler***, as shown in the example code below.

```php
<?php

namespace App\Exceptions;

...
use Juanyaolin\ApiResponseBuilder\Facades\ExceptionRenderer;
...

class Handler extends ExceptionHandler
{
    ...

    public function register(): void
    {
        ...

        /**
         * Pass the renderer as a closure into the renderable method
         */
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return ExceptionRenderer::render($e, $request);
            }
        });

        ...
    }

    ...
}
```

Now, Laravel will respond with the same payload structure while exception thrown, if `Accept: application/json` is set in request header.

> [!TIP]
> There is a middleware named [***ForcedAcceptJson***](../../src/Middleware/ForcedAcceptJson.php) provided to set `Accept: application/json` to the request header, feel free to use it. More information about using middleware, please see [official documentation](https://laravel.com/docs/10.x/middleware).

If you want to use a custom renderer, please see [Configuration](./configuration.md) for more details.

## Exceptions

This library has not made any changes to the exceptions provided by Laravel, except rendering exception. Therefore, you can still use exceptions normally.

Though that, there is an exception named `ApiException` provided for centrally managing information respond to client, with ApiCode. Furthermore, the entire exception rendering mechanism provided by this library is designed to achieve this goal.

There is an example following for using `ApiException`, where the used ApiCode is in [ApiCode](#apicode) section.

```php
<?php

namespace App\Exceptions;

use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Exceptions\ApiException;

class UserLoginFailedException extends ApiException
{
    protected function apiCodeEnum()
    {
        return config(Constant::CONF_KEY_API_CODE_CLASS)::UserLoginFailed;

        // Or you can use a simpler way
        // return \App\Enums\ApiCode::UserLoginFailed;
    }
}
```

From the example above, it is strongly recommended to use the `config()` helper function to access the ApiCode enumeration. Although this may make it hard to track the source, it ensures that the entire project uses the same enumeration. A more developer-friendly alternative is to ensure that the ApiCode enumeration used throughout the project matches the configuration in the [configuration](./configuration.md). This will make it more convenient for developers to use the ApiCode enumeration, but it may cause more time spent when changing the enumeration used in the future. Both two way have their pros and cons, so choose the one that best suits your needs.

## ApiCode

> [!TIP]
> Here, only an example of **PHP native enumeration** is provided. If you are using the [myclabs/php-enum](https://github.com/myclabs/php-enum) library, please adjust the example accordingly.

There is an ApiCode enumeration example following, and assuming it is created in the ***app/Enums*** folder. If you need more details for customizing ApiCode, please see [Configuration](./configuration.md).


```php
<?php

namespace App\Enums;

use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Symfony\Component\HttpFoundation\Response;

enum ApiCode: string implements ApiCodeContract
{
    case Success = 'SUCCESS';
    case Error = 'ERROR';
    case UncaughtException = 'UNCAUGHT_EXCEPTION';
    case ValidationException = 'VALIDATION_EXCEPTION';
    case AuthenticationException = 'AUTHENTICATION_EXCEPTION';
    case HttpException = 'HTTP_EXCEPTION';
    case ApiException = 'API_EXCEPTION';

    case UserLoginFailed = 'USER_LOGIN_FAILED';

    public function apiCode()
    {
        return $this->value;
    }

    public function statusCode(): int
    {
        // These will be used as the response's HTTP status code
        return match ($this) {
            self::Success => Response::HTTP_OK,
            self::UncaughtException => Response::HTTP_INTERNAL_SERVER_ERROR,
            self::ValidationException => Response::HTTP_UNPROCESSABLE_ENTITY,
            self::AuthenticationException => Response::HTTP_UNAUTHORIZED,

            default::Response::HTTP_BAD_REQUEST,
        };
    }

    public function message(): string
    {
        // These will be used as the message respond to the client when an exception thrown
        return match ($this) {
            self::Success => 'Success',
            self::Error => 'Error',
            self::UncaughtException => 'Server Error',
            self::ValidationException => 'Unprocessable Content',
            self::AuthenticationException => 'Unauthenticated',
            self::HttpException => 'Bad Request',
            self::ApiException => "Error [{$this->value}]",

            self::UserLoginFailed => 'Account or password is incorrect',

            default => 'Error',
        };
    }
}
```