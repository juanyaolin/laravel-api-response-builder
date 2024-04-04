# 回應範例

這個章節所提出的範例都是使用初始設定。若您的Laravel專案已有客製化的設定，請以本章內容為基礎查看您自訂後的各項功能。

[&laquo; 說明文件](./documents.md)

* [成功回應](#成功回應)
* [失敗回應](#失敗回應)
* [例外回應](#例外回應)
* [參數](#參數)
  * [方法參數](#方法參數)
  * [回應參數](#回應參數)
* [由Trait提供方法](#由trait提供方法)

## 成功回應

要返回意味著成功的回應，只需要在控制器(Controller)或路由閉包(Closure)中返回由ApiResponse facade的方法 `success()` 所生成的回應物件(Response)。

```php
<?php

use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;

Route::get('string', function () {
    return ApiResponse::success('This is string');
});

Route::get('object', function () {
    return ApiResponse::success([
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
    ]);
});
```

如此一來，您將會得到如下所示的回應。其中，facade方法與回應的各項參數將會在[參數](#參數)小節中說明。

```json
// from string
{
    "success": true,
    "code": 0,
    "message": "Success",
    "data": "This is string"
}

// from object
{
    "success": true,
    "code": 0,
    "message": "Success",
    "data": {
        "key1": "value1",
        "key2": "value2",
        "key3": "value3"
    }
}
```

## 失敗回應

強烈建議您以「拋出例外」(Throw Exception)的方式來返回失敗，因為這能更輕鬆地中斷程式並返回受到規範的錯誤訊息，具體請查閱[例外處理](./exception.md)。

儘管如此，在此小節仍提供如何使用 `error()` 的範例。

```php
<?php

use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;

Route::get('default', function () {
    return ApiResponse::error();
});

Route::get('message', function () {
    return ApiResponse::error('Something wrong');
});
```

預設情況下，將返回意味著錯誤(Error)的回應。如果您想返回指定錯誤資訊，請在呼叫 `error()` 時傳入message等參數。其中，facade方法與回應的各項參數將會在[參數](#參數)小節中說明。

```json
// from default
{
    "success": false,
    "code": -1,
    "message": "Error",
    "data": null
}

// from message
{
    "success": false,
    "code": -1,
    "message": "Something wrong",
    "data": null
}
```

## 例外回應

在這個小節只提供例外拋出後得到的回應範例，更加詳細的資訊請參考[例外處理](./exception.md)。

```php
<?php

use Juanyaolin\ApiResponseBuilder\Exceptions\ApiException;

Route::get('exception', function () {
    throw new ApiException();
});
```

在例外拋出後，您將得到如下的回應。

```json
{
    "success": false,
    "code": -6,
    "message": "Error [-6]",
    "data": null,
    "debug": {
        "exception": "Juanyaolin\\ApiResponseBuilder\\Exceptions\\ApiException",
        "file": "exception/occured/at",
        "line": 25,
        "trace": [ ... ]
    }
}
```

簡單來說，與直接呼叫 `error()` 之間的差異是
* 不同的apiCode
* 可能包含名為 `debug` 的參數

## 參數

### 方法參數

在使用 `success()` 與 `error()` 方法時，請留意參數的順序與參數名稱，具體可以請查看 [ApiResponse.php](../../src/Facades/ApiResponse.php) 。

方法參數與其說明如下

* `apiCode` (int|string|null) 是來自ApiCode枚舉或ApiCode常數，意味著ApiCode或錯誤代碼。預設情況下，帶入值為null時將依據呼叫的方法決定預設值； `success()` 是整數的 **0** ，而 `error()` 則是整數的 **非0**。

* `statusCode` (int|null) 是HTTP狀態碼。預設情況下，帶入值為null時將依據呼叫的方法決定預設值； `success()` 是 **200** ，而 `error()` 則是 **400**。

* `message` (string|null) 是回應的訊息。預設情況下，帶入值為null時將依據呼叫的方法決定預設值； `success()` 是 **Success** ，而 `error()` 則是 **Error**。

* `data` (mixed): 回應的資料。

* `debugData` (array|null) 是偵錯用的資料。預設情況下，繼承以下例外的子類將會帶入 **null** 的值，其他例外則是 **例外的stack**。
  * *Illuminate\Validation\ValidationException*
  * *Illuminate\Auth\AuthenticationException*

* `additional` (array|null) 是額外的資料。這個參數是給開發者使用的，一般而言不會使用到，僅推想或許會有開發需求要傳遞資料到結構格式生成類別(Structure)中操作。

* `httpHeader` (array|null) 是HTTP標頭。預設情況下是空陣列。

* `jsonOptions` (int|null) 是JSON編碼選項。預設情況下，將會帶入來自設定檔的 `builder.encoding_options` 數值。

### 回應參數

回應的結構格式由 `builder.structure` 決定，預設的回應格式請看 [DefaultStructure.php](../../src/Structures/DefaultStructure.php) 。

回應參數與其說明如下

* `success` (boolean) 是意味著API執行成功或失敗。

* `code` (integer) 是意味著ApiCode或錯誤代碼。預設情況下，**0** 意味著API成功執行，而 **非0** 意味著失敗。您可以在 [DefaultApiCodeClass.php](../../src/ApiCodes/DefaultApiCodeClass.php) 與 [DefaultApiCodeEnum.php](../../src/ApiCodes/DefaultApiCodeEnum.php) 中查看預設值，或是自訂您的ApiCode (更詳細的資訊，請查看[配置設定檔](configuration.md))。

* `message` (string) 是API執行後返回的訊息。

* `data` (mixed) 是返回給客戶端的資料。

* `debug` (array) 提供給開發者偵錯(debug)用的資料。預設情況下，這個參數將在滿足以下條件時的例外(Exception)回應中顯示。

  1. 環境變數 `APP_DEBUG` 設置為 ***true***
  2. *ApiResponseBuilder* 的 `debugData` 屬性不是 ***null***
  3. 引發的例外(Exception)不是以下例外的子類 (希望這能讓結果看起來與Laravel原生的例外處理相似)
     * *Illuminate\Validation\ValidationException*
     * *Illuminate\Auth\AuthenticationException*

## 由Trait提供方法

除了透過ApiResponse facade建立回應之外，套件透過trait提供與facade相同的方法給開發者使用。可以在控制器(Controller)中引入trait `HasApiResponseMethods` 後，以不同的方式呼叫 `success()` 和 `error()`。

舉例來說，在 ***App\Http\Controllers\Controller*** 中引入trait

```php
<?php

...

use Juanyaolin\ApiResponseBuilder\Traits\HasApiResponseMethods;

...

class Controller extends BaseController
{

    ...

    use HasApiResponseMethods;

    ...

}
```

> [!NOTICE]
> 在Laravel 11.x後， ***App\Http\Controllers\Controller*** 變成了獨立的抽象類別，但方法的引入方式並未改變。

隨後，所有繼承了 *App\Http\Controllers\Controller* 的控制器(Controller)物件將包含 `success()` 和 `error()` 方法。也就是說，現在您能透過 **$this** 呼叫這兩個方法。

```php
<?php

class MyClassController extends Controller
{
    /**
     * 呼叫由Trait提供的success方法
     */
    public function successCase()
    {
        return $this->success();
    }

    /**
     * 呼叫由Trait提供的error方法
     */
    public function errorCase()
    {
        return $this->error();
    }
}
```
