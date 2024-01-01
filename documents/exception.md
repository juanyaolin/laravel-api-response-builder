# Exception


* Exception
  * [Exception Renderer](#exception-renderer)
  * [Exceptions](#exceptions)
  * [Exception Adaptor](#exception-adaptor)
  * [ApiCode](#apicode)

---

## Exception Renderer

If you would like to response the same structure as `ApiResponse::error()`, you can use exception renderer facade `ExceptionRenderer` in exception handler class ***App\Exceptions\Handler*** as followed code.

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
        /**
         * Add renderer to renderable closure.
         */
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return ExceptionRenderer::render($e, $request);
            }
        });
    }

    ...
}
```

Now, Laravel will response the same structure if request header with `Accept: application/json` setting.

To use your own renderer, please see [Configuration](configuration.md) for more details.

## Exceptions

This package provide a new exception class, i.e. `ApiException`, for customizing exception. You are suggested to throw exceptions which inherits it, for responding with customized api code and message.

For example, generate a new exception for responding user login with incorrect account or message. This example use the customized api code in [ApiCode](#apicode) section below.

```php
<?php

namespace App\Exceptions;

use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstants as Constant;

class UserLoginFailedException extends Exception
{
    protected function apiCodeEnum()
    {
        return config(Constant::CONF_KEY_RENDERER_API_CODES)::UserLoginFailed;
    }
}
```

You are suggested to return api code enumeration case like example above, even thought you can simplely return like followed code. This is because of that your Laravel application will use the same ApiCode enumeration from [Configuration](configuration.md). However, after modified the config and you can simplely use like followed code of course, just make sure the same ApiCode enumeration is used.

```php
...

protected function apiCodeEnum()
{
    return \App\Enums\ApiCode::UserLoginFailed;
}

...
```

## Exception Adaptor

ExceptionRenderer will render exception through `ExceptionAdaptor` class. By default, ExceptionRenderer will process exception as five types, and the type is depended on which exception class is the occurred exception inherited. You can find that which adaptor will be used respectively for those five type in [Configuration](configuration.md).

Of course, you can make your own adaptor for your customized exception group. Please make sure that your adaptor implements ***Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract*** interface, exception renderer needs those methods to generate response. And if your adaptor is done, do not forget to add your adaptor into [config file](configuration.md#adaptors).

There is no example in this section, but you can check out what have done in [ApiExceptionAdaptor.php](../src/ExceptionAdaptors/ApiExceptionAdaptor.php) and [ApiException.php](../src/Exceptions/ApiException.php), and then just do things like that.


## ApiCode

Because of PHP does not allow inheritance feature on enumeration, you should create your own `ApiCode` enumeration for customizing api code. And notice that, your ApiCode enumeration **must** implement ***Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract*** interface, and add cases which is provided in `DefaultApiCode`. Actually, copy it and modify will make it faster.

There is an example followed, that we create a ApiCode enumeration in *app/Enums* folder.

```php
<?php

namespace App\Enums;

use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Symfony\Component\HttpFoundation\Response;

enum ApiCode: int implements ApiCodeContract
{
    case Success = 0;
    case UncaughtException = -1;
    case ValidationException = -2;
    case AuthenticationException = -3;
    case HttpException = -4;
    case ApiException = -5;

    // Customize an user login failed api code
    case UserLoginFailed = -100;

    public function statusCode(): int
    {
        return match ($this) {
            self::Success => Response::HTTP_OK,
            self::ValidationException => Response::HTTP_UNPROCESSABLE_ENTITY,
            self::AuthenticationException => Response::HTTP_UNAUTHORIZED,
            self::HttpException => Response::HTTP_BAD_REQUEST,
            self::ApiException => Response::HTTP_BAD_REQUEST,

            // This http status code will be used as response
            self::UserLoginFailed => Response::HTTP_UNAUTHORIZED

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

            // This message will be show while exception thrown
            self::UserLoginFailed => 'Account or password is incorrect'

            default => 'Server Error',
        };
    }
}
```