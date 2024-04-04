# Package Installation

This chapter will guide you on how to install the package, enable it, and publish configuration files for possible future customization needs.

[&laquo; Documentation](./documents.md)

* [Installation](#installation)
* [Setup](#setup)
* [Publishing Configuration](#publishing-configuration)

## Installation

To install the package, all you need to do is run the following command in root folder of your Laravel project:

```bash
composer require juanyaolin/laravel-api-response-builder
```

## Setup

This project supports Laravel's auto-discovery feature, and it is automatically enabled upon installation.

If auto-discovery doesn't work, manually add `ApiResponseBuilderServiceProvider` to the `providers` array. It's a little bit different between Laravel version *less than 10.x* and *upper than 11.x*.


### For version less than Laravel 10.x

You need to add Service Provider class to providers array in ***config/app.php***, like the following code:

```php
return [
    ...

    'providers' => ServiceProvider::defaultProviders()->merge([

        ...

        /*
        * Customize Service Providers...
        */
        \Juanyaolin\ApiResponseBuilder\ApiResponseBuilderServiceProvider::class
    ])->toArray(),

    ...
];
```

### For version upper than Laravel 11.x

The providers array is moved to ***bootstrap/providers.php***, so you should add Service Provider class to return array of the file, as following:

```php
return [
    ...

    /*
     * Customize Service Providers...
     */
    \Juanyaolin\ApiResponseBuilder\ApiResponseBuilderServiceProvider::class,

    ...
];
```

## Publishing Configuration

Although not mandatory, it is recommended to publish the configuration file for potential future customization needs. For more detailed information on customization, please refer to [Configuration Settings](./configuration.md).

You can use the following command to publish the configuration file:

```bash
php artisan vendor:publish --provider="Juanyaolin\ApiResponseBuilder\ApiResponseBuilderServiceProvider"
```