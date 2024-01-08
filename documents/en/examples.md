# Response Examples

This chapter will provide examples with the initial configuration. If your Laravel project has changed configurations, please delve into your customized features with this chapter as baseline.

[&laquo; Documentation](./documents.md)

* [Success](#success)
* [Error](#error)
* [Exception](#exception)
* [Parameters](#parameters)
  * [Method Parameters](#method-parameters)
  * [Response Parameters](#response-parameters)
* [Using Methods From Trait](#using-methods-from-trait)

## Success

To respond as api processing successful, just return the response object built by facade method `ApiResponse::success()` in your controller or route closure.

```php
<?php

use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;

Route::get('string', function () {
    return ApiResponse::success('This is a string');
});

Route::get('object', function () {
    return ApiResponse::success([
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
    ]);
});
```

Now, you will get the following responses. The parameters of the facade method and the response are introduced in the [Parameters](#parameters) section.

```json
// from string
{
    "success": true,
    "code": 0,
    "message": "Success",
    "data": "This is a string"
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

## Error

It is strongly recommended to repond failures by "throwing exceptions", because it allows for easier interruption and returning standardized error messages. For more details, please see [Exception Handling](./exception.md).

Nevertheless, this section provides examples of `ApiResponse::error()`.

```php
<?php

use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;

Route::get('default', function () {
    return ApiResponse::error();
});

Route::get('message', function () {
    return ApiResponse::error('Something went wrong');
});
```

By default, this will return a response indicating an error or failure. If you want to respond with specific information, calling `ApiResponse::error()` with arguments such like `message`. The parameters of the facade method and the response are introduced in the [Parameters](#parameters) section.

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
    "message": "Something went wrong",
    "data": null
}
```

## Exception

This section only show an response example after exception thrown. For more details, please see [Exception Handling](./exception.md).

```php
<?php

use Juanyaolin\ApiResponseBuilder\Exceptions\ApiException;

Route::get('exception', function () {
    throw new ApiException();
});
```

After throwing an exception, you will get the following response.

```json
{
    "success": false,
    "code": -6,
    "message": "Error [-6]",
    "data": null,
    "debug": {
        "exception": "Juanyaolin\\ApiResponseBuilder\\Exceptions\\ApiException",
        "file": "exception/occurred/at",
        "line": 25,
        "trace": [ ... ]
    }
}
```

In summary, the differences between calling `ApiResponse::error()` directly are:
* Different apiCode
* Possibly contains a parameter named `debug`

## Parameters

### Method Parameters

When calling `ApiResponse::success()` and `ApiResponse::error()`, please check out the order and names of the arguments. For more details, please delve into [ApiResponse.php](../../src/Facades/ApiResponse.php).

The method parameters and their explanations are as follows:

* `apiCode` (int|string|null) comes from the ApiCode enumeration or ApiCode constants, and meaning as ApiCode or error code. By default, when the value is passed as null, it will be filled based on the method called, i.e. `success()` with integer **0** and `error()` with **non-zero** integer.

* `statusCode` (int|null) is the HTTP status code. By default, when the value is passed as null, it will be filled based on the method called, i.e. `success()` with **200** and `error()` with **400**.

* `message` (string|null) is the message responded after API processed. By default, when the value is passed as null, it will be determined based on the method called, i.e. `success()` with **Success** and `error()` with **Error**.

* `data` (mixed) is the data responded to client.

* `debugData` (array|null) is data for debugging. By default, this parameter will have a value of **null**, unless the thrown exception is subclass of the following exceptions will have **debug_trace**.
  * *Illuminate\Validation\ValidationException*
  * *Illuminate\Auth\AuthenticationException*

* `additional` (array|null) is additional data. This parameter is for developers and is generally not used. It is provided in case there is a need to pass data to the structure generation class (ResponseStructure).

* `httpHeader` (array|null) is the HTTP headers. By default, it is an empty array.

* `jsonOptions` (int|null) is JSON encoding options. By default, the `builder.encoding_options` value in the configuration will be used.

### Response Parameters

The response payload structure is determined by `builder.structure`, and the default response format is in [DefaultStructure.php](../../src/Structures/DefaultStructure.php).

The response parameters and their explanations are as follows:

* `success` (boolean) indicates whether if the API processed successful or failed.

* `code` (integer) represents ApiCode or error code. By default, **0** indicates a successful API execution, and **non-zero** indicates failure. You can check the default values in [DefaultApiCodeClass.php](../../src/ApiCodes/DefaultApiCodeClass.php) and [DefaultApiCodeEnum.php](../../src/ApiCodes/DefaultApiCodeEnum.php) or customize your ApiCode (for more details, see [Configuration](configuration.md)).

* `message` (string) is the message responded after API processed.

* `data` (mixed) is the data responded to the client.

* `debug` (array) provides data for developers to debugging. By default, this parameter will be presented in exception responses with following conditions.

  1. The environment variable `APP_DEBUG` is set to **true**.
  2. The `debugData` property of *ApiResponseBuilder* is not **null**.
  3. The thrown exception is not a subclass of the following exceptions (this is to make the result look similar to Laravel's native exception handling).
     * *Illuminate\Validation\ValidationException*
     * *Illuminate\Auth\AuthenticationException*

## Using Methods From Trait

In addition to creating responses through the ApiResponse facade, the library provides methods with the same functionality via a trait. By using the `HasApiResponseMethods` trait in your controller, you can call `success()` and `error()` in different ways.

For example, use the trait in ***App\Http\Controllers\Controller***:

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

Now, all controllers that inherit *App\Http\Controllers\Controller* will have the `success()` and `error()` methods. This means you can call these two methods by **$this**.

```php
<?php

class MyClassController extends Controller
{
    /**
     * Calling the success method provided by the trait
     */
    public function successCase()
    {
        return $this->success();
    }

    /**
     * Calling the error method provided by the trait
     */
    public function errorCase()
    {
        return $this->error();
    }
}
```