# Installation

* Installation
  * [Package installation](#package-installation)
  * [Setup](#setup)
  * [Publishing the config file](#publishing-the-config-file)

---

## Package installation

To install package, all you need to do is using following command.

```
composer require juanyaolin/laravel-api-response-builder
```

## Setup

This package supports Laravel's auto-discovery feature and it's ready to use once installed.

If not, add `ApiResponseBuilderServiceProvider` to providers array in `config/app.php` as following code.

```
...

'providers' => ServiceProvider::defaultProviders()->merge([

    ...

    /*
     * Customize Service Providers...
     */
    Juanyaolin\ApiResponseBuilder\ApiResponseBuilderServiceProvider::class
])->toArray(),

...
```

## Publishing the config file

This is optional. You will need to modify configuration, if you want to use your own `ApiResponse` or `ExceptionRenderer` instance. Besides, it is highly recommended that customize your own the api codes for more usable response while exception occurred.

 You can publish the config file using the following command:

```
php artisan vendor:publish --provider="Juanyaolin\ApiResponseBuilder\ApiResponseBuilderServiceProvider"
```
