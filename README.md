# Laravel API Response Builder

> [!TIP]
> 這是英文版的README，若需要中文版前往[此處](README.zh-TW.md)查閱。

`Api Response Builder` (i.e. this project) is a Laravel library to help developers make JSON API responses easily, normalized, customized.

`ApiResponseBuilder` is inspired by the [marcin-orlowski/laravel-api-response-builder](https://github.com/MarcinOrlowski/laravel-api-response-builder) with adjustments made to its features.

> [!CAUTION]
> This project is still in development, and it's not recommended to use it until version 1.0.0 (official release).


## Installation

To install package, run with composer:

```
composer require juanyaolin/laravel-api-response-builder
```

## Basic usage

After installing, `ApiResponse` facade is ready for building response.

```php
use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;

...

// Success response
return ApiResponse::success();

// Error response
return ApiResponse::error();
```

And you will get following response.

```json
// success
{
    "success": true,
    "code": 0,
    "message": "Success",
    "data": null,
}

// error
{
    "success": false,
    "code": -1,
    "message": "Error",
    "data": null
}
```

More detailed information can be found in [documents](documents/documents.md).