<?php

namespace Juanyaolin\ApiResponseBuilder;

use Illuminate\Support\ServiceProvider;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseStructureContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionRendererContract;
use Juanyaolin\ApiResponseBuilder\Exceptions\LackedEnumCaseOfApiCodeException;
use Juanyaolin\ApiResponseBuilder\Exceptions\ShouldBeEnumException;
use Juanyaolin\ApiResponseBuilder\Exceptions\ShouldBeSubclassOfContractException;
use MyCLabs\Enum\Enum;
use ReflectionClass;
use UnitEnum;

class ApiResponseBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->configPath(), Constant::CONF_CONFIG);

        if ($this->validateConfigClass()) {
            $this->registerSingletons();
        }
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
     * Validate classes in configuration.
     */
    protected function validateConfigClass(): bool
    {
        $this->validateImplementation();
        $this->validateEssentialApiCode();

        return true;
    }

    /**
     * Validate implementation of classes in configuration.
     *
     * @throws ShouldBeSubclassOfContractException
     * @throws ShouldBeEnumException
     */
    protected function validateImplementation(): void
    {
        $configs = [
            [
                'class' => config(Constant::CONF_KEY_RESPONSE_CLASS),
                'interface' => ApiResponseContract::class,
            ],
            [
                'class' => config(Constant::CONF_KEY_RENDERER_CLASS),
                'interface' => ExceptionRendererContract::class,
            ],
            [
                'class' => config(Constant::CONF_KEY_API_CODE_CLASS),
                'interface' => ApiCodeContract::class,
            ],
            [
                'class' => config(Constant::CONF_KEY_BUILDER_STRUCTURE),
                'interface' => ApiResponseStructureContract::class,
            ],
        ];

        foreach (config(Constant::CONF_KEY_RENDERER_ADAPTORS) as $adaptorConfig) {
            $configs[] = [
                'class' => $adaptorConfig[Constant::KEY_ADAPTOR],
                'interface' => ExceptionAdaptorContract::class,
            ];
        }

        foreach ($configs as $config) {
            throw_unless(
                is_subclass_of($config['class'], $config['interface']),
                ShouldBeSubclassOfContractException::class,
                $config['class'],
                $config['interface'],
            );

            // Checking if ApiCode class is an enumeration.
            if ($config['interface'] === ApiCodeContract::class) {
                $isEnum = is_subclass_of($config['class'], Enum::class);
                $isUnitEnum = is_subclass_of($config['class'], UnitEnum::class);

                throw_unless(
                    $isEnum || $isUnitEnum,
                    ShouldBeEnumException::class,
                    $config['class']
                );
            }
        }
    }

    /**
     * Validate essential api codes.
     *
     * @throws LackedEnumCaseOfApiCodeException
     */
    protected function validateEssentialApiCode(): void
    {
        $class = config(Constant::CONF_KEY_API_CODE_CLASS);
        $essential = Constant::ESSENTIAL_API_CODES;

        $reflected = new ReflectionClass($class);
        $lack = array_diff($essential, array_keys($reflected->getConstants()));

        throw_unless(
            empty($lack),
            LackedEnumCaseOfApiCodeException::class,
            $class,
            $lack,
        );
    }

    /**
     * Register singletons to container.
     */
    protected function registerSingletons(): void
    {
        $singletons = [
            [
                'abstract' => config(Constant::CONF_KEY_RESPONSE_FACADE),
                'concrete' => config(Constant::CONF_KEY_RESPONSE_CLASS),
            ],
            [
                'abstract' => config(Constant::CONF_KEY_RENDERER_FACADE),
                'concrete' => config(Constant::CONF_KEY_RENDERER_CLASS),
            ],
        ];

        foreach ($singletons as $singleton) {
            $this->app->singleton($singleton['abstract'], function () use ($singleton) {
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
