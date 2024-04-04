# 套件安裝

本章節將說明如何安裝套件、啟用套件，以及發布設定檔供日後可能發生的客製化需求使用。

[&laquo; 說明文件](./documents.md)

* [安裝](#安裝)
* [啟用](#啟用)
* [發布設定檔](#發布設定檔)

## 安裝

若要安裝套件，您所要做的事情只有在Laravel專案路徑中執行指令

```bash
composer require juanyaolin/laravel-api-response-builder
```

## 啟用

本專案支援Laravel的自動發現(auto-discovery)功能，安裝後便已自動啟用。

如果沒有自動啟用，請手動將 `ApiResponseBuilderServiceProvider` 加到providers陣列中。由於Laravel v11.x大幅調整了檔案結構並重構application的基礎，因而新舊版本的providers陣列的檔案位置有所不同。

### Laravel 10.x 前的版本

對於過往的版本，請將Service Provider類別加到 ***config/app.php*** 的providers陣列中，如下程式碼。

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

### Laravel 11.x 後的版本

對於較新的版本，請將Service Provider類別加到 ***bootstrap/providers.php*** 的返回陣列中，如下程式碼。

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

## 發布設定檔

雖然不是必要的，但仍建議您發布設定檔以滿足未來可能會有的客製化需求。關於客製化更詳細的資訊，請查閱[配置設定檔](./configuration.md)。

您可以使用指令發布設定檔

```bash
php artisan vendor:publish --provider="Juanyaolin\ApiResponseBuilder\ApiResponseBuilderServiceProvider"
```
