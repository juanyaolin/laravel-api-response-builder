<?php

namespace Juanyaolin\ApiResponseBuilder;

use Illuminate\Support\ServiceProvider;

class ApiResponseBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath(), 'api-response-builder');

        $this->app->singleton($this->config('response.facade'), function () {
            return $this->app->make($this->config('response.class'));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishConfig();
        }
    }

    /**
     * Publish default configuration to config path.
     */
    protected function publishConfig(): void
    {
        $this->publishes([
            $this->configPath() => config_path('api-response-builder.php'),
        ], 'api-response-builder.config');
    }

    private function configPath(): string
    {
        return __DIR__ . '/../config/api-response-builder.php';
    }

    private function config(string $key): string
    {
        return config('api-response-builder.' . $key);
    }
}
