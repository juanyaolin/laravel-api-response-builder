# Examples

* Examples
  * [Success](#success)
  * [Error](#error)
  * [Parameters](#parameters)
  * [Methods from Trait](#methods-from-trait)

---

The following examples assume that the package is installed properly and available to your Laravel application. See [Installation](installation.md) and [Configuration](configuration.md) for more details.

## Success

To create a response indicating success, just using the `success()` method of `ApiResponse` facade and return it in controller or route handle closure.

```php
<?php

use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;

Route::get('/string', function () {
    return ApiResponse::success('string');
});

Route::get('/object', function () {
    return ApiResponse::success([
        'key1' => 'value1',
        'key2' => 'value2',
        'key3' => 'value3',
    ]);
});
```

And then, you will get response as followed. The meaning of each parameter in the response will be explained in the [Parameters](#parameters) section.

```json
// from string
{
    "success": true,
    "code": 0,
    "message": "Success",
    "data": "string"
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

It is strongly recommended to return failure response by throwing exception (see [Exception](exception.md)), because it allows for easier program interruption and return specific error information. But nevertheless, examples of how to use the `error()` method is followed.

```php
<?php

use Juanyaolin\ApiResponseBuilder\Facades\ApiResponse;

Route::get('/default', function () {
    return ApiResponse::error();
});

Route::get('/specify', function () {
    return ApiResponse::error(-4);
});
```

By default, the error response return information indicating a server error. If you want to specify the error, call `error()` with arguments such as `apiCode`. The meaning of each parameter in the response will be explained in the [Parameters](#parameters) section.

```json
// from default
{
    "success": false,
    "code": -1,
    "message": "Server Error",
    "data": null
}

// from specify
{
    "success": false,
    "code": -4,
    "message": "Bad Request",
    "data": null
}
```

## Exception

This section only show example of exception throwing. See [Exception](exception.md) for more details.

```php
<?php

use Juanyaolin\ApiResponseBuilder\Exceptions\ApiException;

Route::get('/exception', function () {
    throw new ApiException();
});
```

After exception thrown, you will get response as followed. In essence, it may just include an additional parameter `debug`.

```json
{
    "success": false,
    "code": -5,
    "message": "Error [-5]",
    "data": null,
    "debug": {
        "exception": "Juanyaolin\\ApiResponseBuilder\\Exceptions\\ApiException",
        "file": "exception/occured/at",
        "line": 25,
        "trace": [ ... ]
    }
}
```

## Parameters


* `success` (boolean): Indicating whether the API executed successfully.

* `code` (integer): Meaning as api code or error code. By default, **0** means the API executed successfully, and **non-zero** values as failure. Check out default value in [DefaultApiCode](../src/DefaultApiCode.php), or you can customize your owned ApiCode (see [Configuration](configuration.md) for more details).

* `message` (string): The returned message after the API execution.

* `data` (mixed): The data attached to the response and sent back to the client.

* `debug` (array): The debug information for developer. By default, this parameter will only be present while exception thrown with followed conditions
  1. Environment variable `APP_DEBUG` set to **true**
  2. Property `debugData` of *ApiResponseBuilder* is not **null**
  3. Occured exception is not subclass of followed exception class (Hope this makes laravel works as default)
     * *Illuminate\Validation\ValidationException*
     * *Illuminate\Auth\AuthenticationException*

## Methods from Trait

Additional, there is a trait called `HasApiResponseMethods` in *src/Traits* directory. Using trait in controller or other class supply different way to call `success()` and `error()`.

For example, using trait in *App\Http\Controllers\Controller*.

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

Then, controllers inheriting *App\Http\Controllers\Controller* contain `success()` and `error()` methods. Now, you can call them from **$this**.

```php
<?php

class MyClassController extends Controller
{
    /**
     * An example for returning success response by trait methods.
     */
    public function successCase()
    {
        return $this->success();
    }

    /**
     * An example for returning error response by trait methods.
     */
    public function errorCase()
    {
        return $this->error();
    }
}
```
