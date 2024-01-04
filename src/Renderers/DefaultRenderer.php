<?php

namespace Juanyaolin\ApiResponseBuilder\Renderers;

use Illuminate\Http\Request;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract;
use Juanyaolin\ApiResponseBuilder\Contracts\ExceptionRendererContract;
use Juanyaolin\ApiResponseBuilder\Traits\HasApiResponseMethods;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class DefaultRenderer implements ExceptionRendererContract
{
    use HasApiResponseMethods;

    /**
     * The adaptor match to $throwable.
     */
    protected ExceptionAdaptorContract $adaptor;

    /**
     * The configs of adaptors.
     */
    protected array $configs;

    /**
     * The exception needs to render.
     */
    protected Throwable $throwable;

    /**
     * The request.
     */
    protected Request $request;

    public function render(Throwable $throwable, Request $request): ?Response
    {
        $this->throwable = $throwable;
        $this->request = $request;

        $this->configs = $this->prepareConfigs();
        $this->adaptor = $this->prepareAdaptor();

        return $this->prepareResponse();
    }

    /**
     * Prepare configs of adaptors.
     */
    protected function prepareConfigs(): array
    {
        $configs = array_replace_recursive(
            Constant::DEFAULT_RENDERER_ADAPTORS,
            config(Constant::CONF_KEY_RENDERER_ADAPTORS)
        );

        // Sort by priority.
        uasort($configs, function (array $arrayA, array $arrayB) {
            $priorityA = $arrayA[Constant::KEY_PRIORITY] ?? 0;
            $priorityB = $arrayB[Constant::KEY_PRIORITY] ?? 0;

            return $priorityB <=> $priorityA;
        });

        return $configs;
    }

    /**
     * Prepare adaptor match to $throwable.
     */
    protected function prepareAdaptor(): ExceptionAdaptorContract
    {
        $adaptor = $this->getExceptionConfig()[Constant::KEY_ADAPTOR];

        return new $adaptor($this->throwable);
    }

    /**
     * Get config of adaptor match to $throwable.
     */
    protected function getExceptionConfig(): array
    {
        $exceptionClass = get_class($this->throwable);

        // Check for exactly matching to class name.
        if (array_key_exists($exceptionClass, $this->configs)) {
            return $this->configs[$exceptionClass];
        }

        // Try with 'is_subclass_of()'.
        foreach (array_keys($this->configs) as $exceptionClass) {
            if ($exceptionClass === Constant::KEY_DEFAULT) {
                continue;
            }

            if (is_subclass_of($this->throwable, $exceptionClass)) {
                return $this->configs[$exceptionClass];
            }
        }

        // Nothing found, return default.
        return $this->configs[Constant::KEY_DEFAULT];
    }

    /**
     * Prepare the response of $throwable.
     */
    protected function prepareResponse(): ?Response
    {
        return $this->error(
            $this->adaptor->apiCode(),
            $this->adaptor->message(),
            $this->adaptor->statusCode(),
            $this->adaptor->debug(),
            $this->adaptor->data(),
            $this->adaptor->additional(),
            $this->adaptor->httpHeaders()
        );
    }
}
