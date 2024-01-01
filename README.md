# Laravel API Response Builder

A api response builder

## Installation

To install package, run with composer:

```
composer require juanyaolin/laravel-api-response-builder
```

## Basic usage

After install, `ApiResponse` facade is ready for returning response. More detailed usage can be found in [document](documents/documents.md).

```php
use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;

...

// Success response
return ApiResponse::success();

// Error response
return ApiResponse::error();
```

