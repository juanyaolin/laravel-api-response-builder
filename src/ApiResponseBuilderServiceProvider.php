<?php

namespace Juanyaolin\ApiResponseBuilder;

use Illuminate\Support\ServiceProvider;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstants as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionRendererContract;
use Juanyaolin\ApiResponseBuilder\Exceptions\ShouldImplementInterfaceException;

class ApiResponseBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath(), Constant::CONF_CONFIG);

        $this->registerSingletons();
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
     * Register singletons to container.
     *
     * @throws ShouldImplementInterfaceException
     */
    protected function registerSingletons(): void
    {
        $singletons = [
            [
                'abstract' => config(Constant::CONF_KEY_RESPONSE_FACADE),
                'concrete' => config(Constant::CONF_KEY_RESPONSE_CLASS),
                'contract' => ApiResponseContract::class,
            ],
            [
                'abstract' => config(Constant::CONF_KEY_RENDERER_FACADE),
                'concrete' => config(Constant::CONF_KEY_RENDERER_CLASS),
                'contract' => ExceptionRendererContract::class,
            ],
        ];

        foreach ($singletons as $singleton) {
            $this->app->singleton($singleton['abstract'], function () use ($singleton) {
                throw_unless(
                    is_subclass_of($singleton['concrete'], $singleton['contract']),
                    ShouldImplementInterfaceException::class,
                    $singleton['concrete'],
                    $singleton['contract'],
                );

                return $this->app->make($singleton['concrete']);
            });
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
}
