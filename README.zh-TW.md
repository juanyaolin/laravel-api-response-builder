# Laravel API Response Builder

> [!TIP]
> This is written by chinese, README in english is [here](README.md).

`ApiResponseBuilder` (本專案)旨在提供開發者輕鬆建構標準化的JSON API回應，並提供開發者相當程度的客製化機制。

`ApiResponseBuilder` 是受到 [MarcinOrlowski專案](https://github.com/MarcinOrlowski/laravel-api-response-builder) 的啟發並對其中的功能做調整。

> [!CAUTION]
> 此專案仍處於開發階段，在v1.0.0(正式版)發布以前不建議您使用此專案。

## 套件安裝

要安裝套件，只需要執行以下指令:

```
composer require juanyaolin/laravel-api-response-builder
```

## 基本使用

在套件安裝後，便可以使用 `ApiResponse` facade 來建構回應。

```php
use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;

...

// 成功的回應
return ApiResponse::success();

// 失敗的回應
return ApiResponse::error();
```

而您將得到如下的回應內容。

```json
// 成功
{
    "success": true,
    "code": 0,
    "message": "Success",
    "data": null
}

// 失敗
{
    "success": false,
    "code": -1,
    "message": "Error",
    "data": null
}
```

關於本專案更詳細的資訊，請查閱[文件](documents/zh-TW/documents.md)。