# 例外處理

本章節將介紹如何啟用例外渲染器、如何使用ApiException例外類，以及提供ApiCode的客製化範例。

[&laquo; 說明文件](./documents.md)

* [例外渲染器](#例外渲染器)
* [例外](#例外)
* [ApiCode](#apicode)

## 例外渲染器

如果您已經使用了本專案提供的 `ApiResponse` facade來返回成功或失敗的回應，相信您的客戶端不會希望收到非JSON或不同結構格式的回應導致非預期的錯誤發生，因此強烈建議您也啟用例外渲染器(Exception Renderer)以確保來自Laravel專案的回應具有同樣的結構格式。

啟用例外渲染器的方式很簡單，請在Laravel專案的例外處理類 ***App\Exceptions\Handler*** 中，將渲染器facade的方法 `ExceptionRenderer::render()` 以閉包(Closure)的方式，在 `register()` 方法內帶入 `renderable()` 方法，就如下方的範例程式。

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
         * 將渲染器以閉包的方式帶入 renderable 方法中
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

至此，您的客戶端提交的請求標頭(Request Header)中要求回應是json `Accept: application/json` 的話，Laravel將會在例外觸發時返回相同的結構格式。

> [!TIP]
> 我們額外提供名為 [***ForcedAcceptJson***](../../src/Middleware/ForcedAcceptJson.php) 的中間件(Middleware)使Laravel在請求標頭中添加 `Accept: application/json`，可以根據您的需求決定是否使用它。關於中間件的使用方式請參考[官方說明](https://laravel.com/docs/10.x/middleware)。

若您想使用自訂的渲染器，請前往[配置設定檔](./configuration.md)深入了解如何客製化。

## 例外

本專案除了回應的生成方式之外，沒有對原本Laravel提供的例外做任何改動，因此您仍然可以使用官方提供的方式使用例外。

話雖如此，本專案定義了名為 `ApiException` 的例外類供您結合ApiCode機制來統一管理返回客戶端的資訊。更進一步的說，本專案所提供的整套例外渲染機制就是為了達成這一目的而設計。

接下來，提供範例給您參考，範例中所使用到的ApiCode能在[ApiCode](#apicode)小節中看到。

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

        // 或是您可以用比較簡單的方式
        // return \App\Enums\ApiCode::UserLoginFailed;
    }
}
```

由以上範例可以得知，本專案強烈地建議您使用輔助方法 `config()` 來操作ApiCode枚舉。雖然這將導致難以追蹤來源，但它能確保整個專案使用相同的枚舉。而對開發者較為友善的替代方案是，確保您實際在專案各處使用的ApiCode枚舉與[設定檔](./configuration.md)中的配置相同即可。這將使開發者能更加方便的使用ApiCode枚舉，但可能會增加未來變更使用的枚舉時的時間花費。兩種方法各有利弊，請根據您的開發需求決定使用的方式。

## ApiCode

> [!TIP]
> 這裡只提供 **PHP原生枚舉** 的範例，若您使用套件 [myclabs/php-enum](https://github.com/myclabs/php-enum) 則請根據範例加以調整。

這裡將只會提出ApiCode的範例，若您想要詳細了解如何自訂ApiCode枚舉，建議您先看完[配置設定檔](./configuration.md)當中的說明。

下方便是作為範例的ApiCode枚舉，並假設它被建立在 ***app/Enums*** 目錄中。

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
        // 這些將會作為回應的http status code
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
        // 這些將會作為例外被觸發時，返回給客戶端的回應中的訊息
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