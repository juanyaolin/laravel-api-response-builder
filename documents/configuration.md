# Configuration

* Configuration
  * [Preparation](#preparation)


## Preparation

Before starting to configure, you need to [publish configuration file](installation.md#publishing-the-config-file) to your Laravel application.

## Options

Please note, that in majority of use cases it should be perfectly sufficient to just use defaults and only tune the config when needed. Available configuration options and its explain were followed.

* [response](#response)
  * [facade](#facade)
  * [class](#class)
* [builder](#builder)
  * [structure](#structure)
  * [encoding_options](#encoding_options)
* [exception_renderer](#exception_renderer)
  * [facade](#facade-1)
  * [class](#class-1)
  * [adaptors](#adaptors)

### response

The configuration for `ApiResponse` facade.

#### facade

The name for application binding singleton instance to  `ApiResponse` facade, and could be customized if the name have been used by other facades. You can find how this be used in [ApiResponseBuilderServiceProvider.php](../src/ApiResponseBuilderServiceProvider.php) and [ApiResponse.php](../src/Facades/ApiResponse.php).

#### class

Declare which class will be bound to `ApiResponse` facade as singleton instance. Make your own class if default class provided by package is not satisfy your usage, and notice that customized ApiResponse class must implement ***Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseContract*** interface to make it working normally.

### builder

The configuration for `ApiResponseBuilder` class.

#### structure

The data structure of returned response,. If the default structure doesn't suit your usage, feel free to create a new structure that you need. Please make sure your structure class have **inherited**(not implement) abstract class ***Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseStructureContract***, and do not customize `__construct()` method. After that, don't forget replace your structure class to configuration file. For more details, you can see `setupResponseStructure()` method [ApiResponseBuilder.php](../src/ApiResponseBuilder.php)

#### encoding_options

It's the json encoding options, feel free to modify if needed. This value will be used if you do not specify `jsonOptions` argument in ApiResponse facade methods.

### exception_renderer

The configuration for `ExceptionRenderer` facade. To enable exception renderer in Laravel application, please see [Exception](exception.md).

#### facade

Like option [response.facade](#facade) above, this is name for `ExceptionRenderer` facade. And its usage can be found in [ApiResponseBuilderServiceProvider.php](../src/ApiResponseBuilderServiceProvider.php) and [ExceptionRenderer.php](../src/Facades/ExceptionRenderer.php).

#### class

Like option [response.class](#class) above, it's the class for `ExceptionRenderer` facade. And notice that customized ExceptionRenderer class must implement ***Juanyaolin\ApiResponseBuilder\Contracts\ExceptionRendererContract***.

#### adaptors

This is the most important part for exception renderer (I think). There are some adaptors have provided for normal usage, and which adaptor will be used is depenced on the exception that the occured exception have inherited.

Package have handled following exception group

* *Juanyaolin\ApiResponseBuilder\Exceptions\ApiException*
* *Illuminate\Validation\ValidationException*
* *Symfony\Component\HttpKernel\Exception\HttpException*
* *Illuminate\Auth\AuthenticationException*

If the occured exception does not belong to any group above, the ***default*** group will be used to render exception. (Hope this gives developers the same experience as original Laravel exception renderer)

There are two keys for each group, i.e. `adaptor` and `priority`

* `adaptor` determines which exception adaptor class will be used to render the occured exception. If provided adaptor does not suit your usage, feel free to create your own adaptor, see [Exception Adaptor](exception.md#exception-adaptor) for more information about how to customize adaptor.
* `priority` determines the order of exception groups. The one with higher priority will be used first, if there are groups match the occured exception.

You can find how this be used in [DefaultExceptionRenderer.php](../src/DefaultExceptionRenderer.php).