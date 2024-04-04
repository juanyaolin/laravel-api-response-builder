# Configuration

This chapter will explain the meanings and purposes of each option in the configuration file, as well as how to customize them.

[&laquo; Documentation](./documents.md)

* [Prerequisites](#prerequisites)
* [Configuration Options](#configuration-options)

## Prerequisites

Before configuring, make sure to [publish](./installation.md#publishing-configuration) the configuration file to your Laravel project. After publishing, the configuration file `api-response-builder.php` will be located in the ***config*** directory of your Laravel project.

## Configuration Options

Please note that in most cases, using the default values and adjusting the options only when necessary is sufficient. The available configuration options and their explanations are as follows.

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

An enumeration class with various ApiCode cases. This will be used in [ApiResponseBuilder.php](../../src/ApiResponseBuilder.php) and various ExceptionAdaptors.

> [!TIP]
> It is highly recommended to customize the ApiCode enumeration to meet actual development needs.

When creating your ApiCode, there are a few things to note:

Firstly, your custom enumeration must "implement" [***ApiResponseContract***](../../src/Contracts/ApiCodeContract.php) for obtaining data from those methods.

Next, in your custom enumeration, you must declare the following cases:

1. `Success` is used as the API execution is successful, and automatically filled in while calling `ApiResponse::success()`.

2. `Error` is used as the API execution is failed, and automatically filled in while calling `ApiResponse::error()`.

   The cases above are used in the constructor of [ApiResponseBuilder.php](../../src/ApiResponseBuilder.php). The following cases are used in their *ExceptionAdaptors* respectively, e.g. `UncaughtException` is used in [DefaultExceptionAdaptor.php](../../src/Adaptors/DefaultExceptionAdaptor.php).

3. `AuthenticationException` is used while an exception thrown, and which inherits [*AuthenticationException*](https://laravel.com/api/10.x/Illuminate/Auth/AuthenticationException.html). AuthenticationException is thrown when accessing routes protected by authentication middleware without logging in. More details can be found in [Laravel Authentication Middleware](https://laravel.com/api/10.x/Illuminate/Auth/Middleware/Authenticate.html#method_authenticate).

4. `ValidationException` is used while an exception thrown, and which inherits [*ValidationException*](https://laravel.com/api/10.x/Illuminate/Validation/ValidationException.html). ValidationException is thrown while the Laravel's Validator, used by [Customized Request](https://laravel.com/docs/10.x/validation#form-request-validation) or [Validation](https://laravel.com/docs/10.x/validation), found something doesn't match with given rules.

5. `HttpException` is used while an exception thrown, and which inherits [*HttpException*](https://github.com/symfony/http-kernel/blob/7.0/Exception/HttpException.php). HttpException may be thrown by Laravel automatically or developer manually, and there are many exceptions inherited it might be thrown during Laravel processing.

6. `ApiException` is used while an exception thrown, and which inherits [*ApiException*](../../src/Exceptions/ApiException.php). Exceptions extends `ApiException` will be thrown by the developer, and neither native Laravel nor this library will throw these exceptions. You can use the exception to group up and centrally manage exception information returning to the client.

7. `UncaughtException` is used while an exception thrown, and which doesn't belong to the cases above. Obviously, most exceptions and errors will be treated as this case.

Finally, if you do not need to use ApiCode, keep this option with the default value. The library will break if this option is lost.

In practice, the simplest way to customize is to copy [DefaultApiCodeEnum.php](../../src/ApiCodes/DefaultApiCodeEnum.php), modify the class name, namespace, and values of each enumeration case.

ApiCode is usually used with exception handling, customized example of ApiCode will be shown in [Exception Handling](./exception.md).

### builder

#### structure

Declares the construction class of the response payload structure to be generated. If the default structure is not usable for you, create your own structure.

To create your custom structure, note the following:

* Ensure that your structure class "extends" [***ApiResponseStructureContract***](../../src/Contracts/ApiResponseStructureContract.php), and **DON'T** modify the `__construct()`.
* Return the payload structure you expect by the `make()`.
* Parameters and their meanings in *ApiResponseStructureContract* can be found in [Method Parameters](./examples.md#method-parameters).

There is an example of customization followed for you. The timestamp is an additional parameter to response, comparing with [DefaultStructure.php](../../src/Structures/DefaultStructure.php).

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

For more details, delve into `setupResponseStructure()` in [ApiResponseBuilder.php](../../src/ApiResponseBuilder.php) with [DefaultStructure.php](../../src/Structures/DefaultStructure.php) or the example above.

#### encoding_options

This is option for json encoding, feel free to change it if needed.  The value here will be used as default, if calling **ApiResponse::success()** or  **ApiResponse::error()** without the `jsonOptions` argument. The available encoding options can be found in the [Official Manual](https://www.php.net/manual/en/function.json-encode.php).

### response

#### facade

The name for Laravel binding singleton instance to `ApiResponse` facade, change it if the name have been used by other facades or in your needs. You can find how this be used in [ApiResponseBuilderServiceProvider.php](../../src/ApiResponseBuilderServiceProvider.php) and [ApiResponse.php](../../src/Facades/ApiResponse.php).

#### class

Declares which class will be bound to the `ApiResponse` facade as a singleton. Create your class which you need, if the provided class does not suit to usage.  However, be aware that your custom class must "implement" [***ApiResponseContract***](../../src/Contracts/ApiResponseContract.php). Parameters and their meanings can be found in [Method Parameters](./examples.md#method-parameters).

### renderer

> [!TIP]
> To use the exception renderer provided by this library, please see [Exception Handling](./exception.md) for more details.

#### facade

Like option [response.facade](#facade), this is the name for Laravel binding singleton instance to `ExceptionRenderer` facade. How this be used can be find in [ApiResponseBuilderServiceProvider.php](../../src/ApiResponseBuilderServiceProvider.php) and [ExceptionRenderer.php](../../src/Facades/ExceptionRenderer.php).

#### class

Like option [response.class](#class-1), this declares which class will be bound to the `ExceptionRenderer` facade as a singleton. Using your custom class if needed, but beware that it must "implement" [***ExceptionRendererContract***](../../src/Contracts/ExceptionRendererContract.php).

As you can see, *ExceptionRendererContract* only requires to implement `render()`. The method needs two arguments, **$throwable** (the thrown exception) and **$request** (the request from client), to render the response. For more details, delve into `render()` of [Laravel Exception Handler](https://laravel.com/api/10.x/Illuminate/Foundation/Exceptions/Handler.html#method_render).

#### adaptors

This is used for the exception renderer. The *ExceptionAdaptor* will be chosen if the thrown exception is subclass of the class show as key and have the highest priority.

The library provides adaptors for the following exceptions:

* *Juanyaolin\ApiResponseBuilder\Exceptions\ApiException*
* *Illuminate\Validation\ValidationException*
* *Symfony\Component\HttpKernel\Exception\HttpException*
* *Illuminate\Auth\AuthenticationException*

If the exception does not belong to any above, the ***"default"*** group will be used. Each group must include the following two keys and their corresponding values:

* `adaptor` declares which adaptor will be used to convert the corresponding exception for the renderer.
* `priority` determines the priority of the corresponding exception group, the highest priority will be used.

How these adaptors are used can be find in [DefaultRenderer.php](../../src/Renderers/DefaultRenderer.php).

If the provided adaptors do not usable for you, create the adaptor you need. Please note a few points:

* Your adaptors must "implement" [***ExceptionAdaptorContract***](../../src/Contracts/ExceptionAdaptorContract.php), to ensure the renderer can use these methods to generate response.
* Ensure the option values of your custom adaptor match the format of the original, as shown below.
  ```php
  ...

  'adaptors' => [
      ...

      '\Name\Of\Exception\For\Your\Adaptor' => [
          'adaptor' => 'YourAdaptorClassName'
          'priority' => 1, // Priority of this adaptor
      ],

      ...
  ],

  ...
  ```

Delve into provided adaptors for more details about adaptor customization, especially [HttpExceptionAdaptor.php](../../src/Adaptors/HttpExceptionAdaptor.php) and [ApiExceptionAdaptor.php](../../src/Adaptors/ApiExceptionAdaptor.php).