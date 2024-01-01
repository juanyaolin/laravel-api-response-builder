<?php

namespace Juanyaolin\ApiResponseBuilder;

use BackedEnum;
use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstants as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseStructureContract;
use Juanyaolin\ApiResponseBuilder\Exceptions\ShouldImplementInterfaceException;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseBuilder
{
    /**
     * Determine if the api is processed successful.
     */
    protected bool $success;

    /**
     * The api code of response.
     */
    protected int $apiCode;

    /**
     * Http status code.
     */
    protected int $statusCode;

    /**
     * The message of response.
     */
    protected string $message;

    /**
     * The data of response.
     */
    protected mixed $data;

    /**
     * The data for debugging.
     */
    protected ?array $debugData;

    /**
     * Additional data for developer customization.
     */
    protected ?array $additional;

    /**
     * The http response headers.
     */
    protected array $httpHeaders;

    /**
     * The options for JSON encoding.
     */
    protected int $jsonOptions;

    /**
     * The variable for response payload.
     */
    protected array $response;

    /**
     * Only can be initiated by asSuccess() and asError().
     */
    protected function __construct(bool $success, int $apiCode)
    {
        $this->success = $success;
        $this->apiCode = $apiCode;
        $this->statusCode = $this->enumOfApiCode($apiCode)->statusCode();
        $this->message = $this->enumOfApiCode($apiCode)->message();
        $this->data = null;
        $this->debugData = null;
        $this->additional = null;
        $this->httpHeaders = [];
        $this->jsonOptions = config(
            Constant::CONF_KEY_BUILDER_ENCODING_OPTIONS,
            Constant::DEFAULT_ENCODING_OPTIONS
        );
        $this->response = [];
    }

    /**
     * Initiate an instance of ApiResponseBuilder as api called successful.
     */
    public static function asSuccess(int $apiCode = null): static
    {
        return new static(true, $apiCode ?? static::defaultApiCode(true));
    }

    /**
     * Initiate an instance of ApiResponseBuilder as api called failed.
     */
    public static function asError(int $apiCode = null): static
    {
        return new static(false, $apiCode ?? static::defaultApiCode(false));
    }

    /**
     * Specify http status code of response.
     */
    public function withStatusCode(int $statusCode = null): static
    {
        if (!is_null($statusCode)) {
            $this->statusCode = $statusCode;
        }

        return $this;
    }

    /**
     * Specify message of response.
     */
    public function withMessage(string $message = null): static
    {
        if (!is_null($message)) {
            $this->message = $message;
        }

        return $this;
    }

    /**
     * Specify data of response.
     */
    public function withData(mixed $data = null): static
    {
        if (!is_null($data)) {
            $this->data = $data;
        }

        return $this;
    }

    /**
     * Sepcify debugData of response.
     */
    public function withDebugData(array $debugData = null): static
    {
        if (!is_null($debugData)) {
            $this->debugData = $debugData;
        }

        return $this;
    }

    /**
     * Bring in the additional information for customized response generating.
     */
    public function withAdditional(array $additional = null): static
    {
        if (!is_null($additional)) {
            $this->additional = $additional;
        }

        return $this;
    }

    /**
     * Customize http response header.
     */
    public function withHttpHeaders(array $httpHeaders = null): static
    {
        if (!is_null($httpHeaders)) {
            $this->httpHeaders = $httpHeaders;
        }

        return $this;
    }

    /**
     * Sepcify json encoding options.
     */
    public function withJsonOptions(int $jsonOptions = null): static
    {
        if (!is_null($jsonOptions)) {
            $this->jsonOptions = $jsonOptions;
        }

        return $this;
    }

    /**
     * Build JsonResponse.
     */
    public function build(): Response
    {
        return $this->setupResponseStructure()->makeResponse();
    }

    /**
     * Default api code by successful.
     */
    protected static function defaultApiCode(bool $success): int
    {
        return $success
            ? config(Constant::CONF_KEY_RENDERER_API_CODES)::Success->value
            : config(Constant::CONF_KEY_RENDERER_API_CODES)::UncaughtException->value;
    }

    /**
     * The enum of provided api code.
     *
     * @return BackedEnum|\Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract
     */
    protected function enumOfApiCode(int $apiCode)
    {
        return config(Constant::CONF_KEY_RENDERER_API_CODES)::tryFrom($apiCode);
    }

    /**
     * Setup the structure of response payload.
     *
     * @throws ShouldImplementInterfaceException
     */
    protected function setupResponseStructure(): static
    {
        $structure = config(Constant::CONF_KEY_BUILDER_STRUCTURE);

        throw_unless(
            is_subclass_of($structure, ApiResponseStructureContract::class),
            ShouldImplementInterfaceException::class,
            $structure,
            ApiResponseStructureContract::class,
        );

        $this->response = (new $structure(
            success: $this->success,
            apiCode: $this->apiCode,
            statusCode: $this->statusCode,
            message: $this->message,
            data: $this->data,
            debugData: $this->debugData,
            additional: $this->additional,
        ))->make();

        return $this;
    }

    /**
     * Make json response by laravel helper function.
     */
    protected function makeResponse(): Response
    {
        return response()->json(
            $this->response,
            $this->statusCode,
            $this->httpHeaders,
            $this->jsonOptions
        );
    }
}
