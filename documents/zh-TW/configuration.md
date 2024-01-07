# 配置設定檔

本章節將介紹設定檔中各個選項的意義、用處，以及如何客製化。

[&laquo; 說明文件](./documents.md)

* [事前準備](#事前準備)
* [設定檔中的選項](#設定檔中的選項)

## 事前準備

在開始配置設定檔之前，請先將設定檔[發布](installation.md#發布設定檔)到您的Laravel專案。發布後，設定檔 `api-response-builder.php` 將在Laravel專案的 ***config*** 目錄下。

## 設定檔中的選項

請注意，在大多數使用情境中，使用預設值並僅在需要時調整設定就足夠了。可用的設定檔選項及其解釋如下

* [api_code](#api_code)
  * [class](#class)
* [builder](#builder)
  * [structure](#structure)
  * [encoding_options](#encoding_options)
* [response](#response)
  * [facade](#facade)
  * [class](#class-1)
* [renderer](#renderer)
  * [facade](#facade-1)
  * [class](#class-2)
  * [adaptors](#adaptors)

### api_code

#### class

包含了各種ApiCode案例的枚舉類，將會使用在 [ApiResponseBuilder.php](../../src/ApiResponseBuilder.php) 和各個ExceptionAdaptor中。

> [!TIP]
> 由於在PHP8.1之前不存在原生的枚舉功能，因而使用套件 [myclabs/php-enum](https://github.com/myclabs/php-enum) 替代。若您的PHP版本在8.1以上，請直接使用原生的枚舉功能開發，此專案在未來將取消對 *myclabs/php-enum* 套件的支援。

強烈建議您自訂ApiCode枚舉以便符合實際開發需求，在建立您的ApiCode時有幾件事需要特別留意。

首先，您自訂的枚舉必須「**實作(implement)**」 [***ApiResponseContract***](../../src/Contracts/ApiCodeContract.php) 使套件能利用特定的方法取得枚舉案例(enum case)的對應資料。

接著，在您自訂的枚舉中必需宣告以下案例

1. `Success` 是作為API執行成功的code，在呼叫ApiResopnse::success()時自動帶入。

2. `Error` 是作為API執行失敗的code，在呼叫ApiResponse::error()時自動帶入。

    以上兩項被使用於 [ApiResponseBuilder.php](../../src/ApiResponseBuilder.php) 的建構函數，而以下幾項則是被用於各自的 *ExceptionAdaptor* 中。例如 `UncaughtException` 使用於 [DefaultExceptionAdaptor.php](../../src/Adaptors/DefaultExceptionAdaptor.php) 。

3. `AuthenticationException` 是當以 [*Laravel的AuthenticationException*](https://laravel.com/api/10.x/Illuminate/Auth/AuthenticationException.html) 為父類別的例外觸發時，自動帶入的code。這組例外通常在用戶尚未登入卻訪問受到用戶驗證中間件(Authenticate Middleware)保護的路由時被觸發，具體資訊可以查閱[Laravel的用戶驗證中間件](https://laravel.com/api/10.x/Illuminate/Auth/Middleware/Authenticate.html#method_authenticate)的 `authenticate()` 與 `unauthenticated()` 方法。

4. `ValidationException` 是當以 [*Laravel的ValidationException*](https://laravel.com/api/10.x/Illuminate/Validation/ValidationException.html) 為父類別的例外觸發時，自動帶入的code。這組例外通常是在Laravel提供的驗證器(Validator)驗證失敗時被觸發，比方說[客製化的請求](https://laravel.com/docs/10.x/validation#form-request-validation)或[手動執行的驗證](https://laravel.com/docs/10.x/validation)。

5. `HttpException` 是當以 [*Symfony的HttpException*](https://github.com/symfony/http-kernel/blob/7.0/Exception/HttpException.php) 為父類別的例外觸發時，自動帶入的code。這組例外可能會由原生Laravel或是開發者手動觸發，許多在Laravel運作時可能觸發的例外便是繼承了這個例外類別。

6. `ApiException` 是當以 [*ApiException*](../../src/Exceptions/ApiException.php) 為父類別的例外觸發時，自動帶入的code。這組例外都將是由開發者自行觸發，原生Laravel或是本專案的功能不會觸發這組例外。您可以利用這個例外組來統一管理您想要返回客戶端的例外資訊。

7. `UncaughtException` 是當被觸發的例外不屬於以上幾項時自動帶入的code，因此絕大多數的例外與錯誤將會被當成這個案例。

最後，若您不需要使用到ApiCode的話，請在此設定選項保留預設值，缺少此設定將會導致套件無法正常運作。

實際上，最簡單的自訂方式是直接複製 [DefaultApiCodeClass.php](../../src/ApiCodes/DefaultApiCodeClass.php) 或 [DefaultApiCodeEnum.php](../../src/ApiCodes/DefaultApiCodeEnum.php) 的內容後，調整枚舉名稱(Class Name)、命名空間(Namespace)及各個枚舉案例所屬的值。

ApiCode在大多情況會與例外處理一起使用，因此自訂ApiCode的範例將在[例外處理](./exception.md)章節中一併展示。

### builder

#### structure

聲明將要生成的回應的資料結構格式的建構類別。如果預設的結構格式不適合您的使用需求，您可以自行建立需要的結構格式。

若您要使用自訂的結構格式，有幾項要點請您留意

* 確保您的結構格式類別「**繼承(extends)**」抽象類別 [***ApiResponseStructureContract***](../../src/Contracts/ApiResponseStructureContract.php) ，並且不要變更 `__construct()` 方法。
* 在 `make()` 方法中返回您所期望的結構格式。
* 在 *ApiResponseStructureContract* 中可以的參數及其意義可以參考[方法參數](./examples.md#方法參數)。

接下來，提供自訂的範例給您參考。與預設的結構格式  [DefaultStructure.php](../../src/Structures/DefaultStructure.php) 相較之下，這個結構格式額外返回了時間戳(Timestamp)。

```php
<?php

namespace App\ResponseBuilder\Structures;

use Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseStructureContract;

class StructureWithTimestamp extends ApiResponseStructureContract
{
    public function make(): array
    {
        $response = [
            'success' => $this->success,
            'code' => $this->apiCode,
            'message' => $this->message,
            'data' => $this->data,
            'timestamp' => now()->getTimestamp(),
        ];

        if (!$this->success && !is_null($this->debugData)) {
            $response['debug'] = $this->debugData;
        }

        return $response;
    }
}
```

若您需要更詳細的資訊，請參考 [ApiResponseBuilder.php](../../src/ApiResponseBuilder.php) 的 `setupResponseStructure()` 方法實際功能，再結合預設的結構格式 [DefaultStructure.php](../../src/Structures/DefaultStructure.php) 或上述範例的結構格式。

#### encoding_options

這是json編碼選項，如果需要可以隨意修改，可用的編碼選項請參考[PHP官方文件](https://www.php.net/manual/en/function.json-encode.php)。如果您沒有在ApiResponse facade的方法中傳入 `jsonOptions` 參數，將以此作為預設值。

### response

#### facade

Laravel綁定 `ApiResponse` facade單例的名稱，您可以在 [ApiResponseBuilderServiceProvider.php](../../src/ApiResponseBuilderServiceProvider.php) 和 [ApiResponse.php](../../src/Facades/ApiResponse.php) 中查看這個設定值如何被使用。如果預設的名稱已被其他facade使用或有其他使用需求，請自由更改。

#### class

聲明哪個類別將作為單例綁定到 `ApiResponse` facade。如果套件提供的預設類別無法滿足您的使用需求時，您可以建立符合您需求的類別，但請留意您自訂的類別必須「**實作(implement)**」 [***ApiResponseContract***](../../src/Contracts/ApiResponseContract.php) 才能正常運作，其中的各項參數及其意義可以參考[方法參數](./examples.md#方法參數)。

### renderer

> [!TIP]
> 想要使用本專案提供的例外渲染器(Exception Renderer)，請前往[例外處理](./exception.md)章節深入了解如何啟用。

#### facade

如同選項[response.facade](#facade)，這是Laravel綁定 `ExceptionRenderer` facade單例的名稱。此設定的具體的使用可以查看 [ApiResponseBuilderServiceProvider.php](../../src/ApiResponseBuilderServiceProvider.php) 和 [ExceptionRenderer.php](../../src/Facades/ExceptionRenderer.php) 。

#### class

如同[response.class](#class-1)，這聲明哪個類別將作為單例綁定到 `ApiResponse` facade。您可以使用自訂的類別，但請留意自訂的類別必須「**實作(implement)**」 [***ExceptionRendererContract***](../../src/Contracts/ExceptionRendererContract.php) 來確保渲染器能正確地被 `ExceptionRenderer` facade使用。

實際上，*ExceptionRendererContract*只要求您實作 `render()` 方法，該方法將帶入 **$throwable** 和 **$request** 兩項參數，分別意味著「被觸發的例外」與「該次用戶端提出的請求」，更具體的資訊可以參考[Laravel例外處理器](https://laravel.com/api/10.x/Illuminate/Foundation/Exceptions/Handler.html#method_render)中的 `render()` 方法。

#### adaptors

這個設定選項被用於例外渲染器(Exception Renderer)，它將會根據被觸發的例外是繼承設定值中的哪個例外來決定使用的適配器(Exception Adaptor) 。

本專案提供了以下幾種例外的適配器

* *Juanyaolin\ApiResponseBuilder\Exceptions\ApiException*
* *Illuminate\Validation\ValidationException*
* *Symfony\Component\HttpKernel\Exception\HttpException*
* *Illuminate\Auth\AuthenticationException*

如果被觸發的例外不屬於上述的幾種例外組，將會使用名為「**預設(*default*)**」的例外組。而每個例外組都必須包含以下兩個鍵(key)與其對應的值

* `adaptor` 聲明哪個適配器將被用來轉換對應的例外給渲染器。
* `priority` 決定對應的例外組優先權，優先權越高的設定將越優先被使用。

您可以在 [DefaultRenderer.php](../../src/Renderers/DefaultRenderer.php) 中了解適配器是如何被使用的。

若本專案提供的適配器無法滿足您的使用需求，您可以自訂所需的適配器，並請留意幾項重點

* 適配器必須「**實作(implement)**」 [***ExceptionAdaptorContract***](../../src/Contracts/ExceptionAdaptorContract.php) ，因為渲染器將會使用到這些方法來生成回應(Response)。
* 確保您自訂的適配器設定值與原本的設定值結構相符，也就是如下方的結構。
  ```php
  ...

  'adaptors' => [
      ...

      '欲使用自訂適配器的例外名稱' => [
          'adaptor' => '您的適配器類別名稱'
          'priority' => 1, // 這個適配器的優先級
      ],

      ...
  ],

  ...
  ```

具體該如何自訂，您可以直接參考本專案提供的各個適配器，尤其是 [HttpExceptionAdaptor.php](../../src/Adaptors/HttpExceptionAdaptor.php) 和 [ApiExceptionAdaptor.php](../../src/Adaptors/ApiExceptionAdaptor.php) 。