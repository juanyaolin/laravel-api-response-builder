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

If auto-discovery doesn't happen, manually add `ApiResponseBuilderServiceProvider` to the `providers` array in ***config/app.php***, like the following code:

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

## Publishing Configuration

Although not mandatory, it is recommended to publish the configuration file for potential future customization needs. For more detailed information on customization, please refer to [Configuration Settings](./configuration.md).

You can use the following command to publish the configuration file:

```bash
php artisan vendor:publish --provider="Juanyaolin\ApiResponseBuilder\ApiResponseBuilderServiceProvider"
```