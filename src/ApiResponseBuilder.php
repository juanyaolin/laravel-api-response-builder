<?php

namespace Juanyaolin\ApiResponseBuilder;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseBuilder
{
    /** Determine if the api is processed successful. */
    protected bool $success;

    /** The api code of response. */
    protected int $apiCode;

    /** Http status code */
    protected int $httpCode;

    /** The message of response */
    protected string $message;

    /** The data of response */
    protected mixed $data;

    /** The data for debugging */
    protected ?array $debugData;

    /** The http response headers */
    protected array $httpHeaders;

    /** The options for JSON encoding */
    protected int $jsonOptions;

    /** The variable for response payload */
    protected array $response;

    /**
     * Only can be initiated by asSuccess() and asError().
     */
    protected function __construct(bool $success, int $apiCode)
    {
        $this->success = $success;
        $this->apiCode = $apiCode;
        $this->data = null;
        $this->debugData = null;
        $this->httpHeaders = [];
        $this->jsonOptions = $this->configKey('encoding_options', 0);
        $this->response = [];

        if ($success) {
            $this->httpCode = Response::HTTP_OK;
            $this->message = 'success';
        } else {
            $this->httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $this->message = "error #{$apiCode}";
        }
    }

    /**
     * Initiate an instance of ApiResponseBuilder as api called successful.
     */
    public static function asSuccess(int $apiCode = null): static
    {
        return new static(true, $apiCode ?? BasicApiCodes::OK);
    }

    /**
     * Initiate an instance of ApiResponseBuilder as api called failed.
     */
    public static function asError(int $apiCode = null): static
    {
        return new static(false, $apiCode ?? BasicApiCodes::UNCAUGHT_EXCEPTION);
    }

    /**
     * Specify http status code of response.
     */
    public function withHttpCode(int $httpCode = null): static
    {
        if (!is_null($httpCode)) {
            $this->httpCode = $httpCode;
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
    public function build(): JsonResponse
    {
        return $this->setupResponseStructure()->makeResponse();
    }

    /**
     * Setup the structure of response payload.
     *
     * Only keys in avaiableKeys() will be used. Also, the key 'debugData' will
     * be used only if and only if environment variable 'app.debug' is true and
     * the property 'debugData' is not null.
     */
    protected function setupResponseStructure(): static
    {
        foreach ($this->configKey('structure') as $key => $value) {
            $value = str_replace('$', '', $value);

            if (!in_array($value, $this->avaiableKeys())) {
                continue;
            }

            if ($value == 'debugData') {
                if (!config('app.debug', false)) {
                    continue;
                }

                if (is_null($this->debugData)) {
                    continue;
                }
            }

            $this->response[$key] = $this->{$value};
        }

        return $this;
    }

    /**
     * Make json response by laravel helper function.
     */
    protected function makeResponse(): JsonResponse
    {
        return response()->json(
            $this->response,
            $this->httpCode,
            $this->httpHeaders,
            $this->jsonOptions
        );
    }

    protected function configKey(string $key, $default = null)
    {
        return config('api-response-builder.builder.' . $key, $default);
    }

    protected function avaiableKeys()
    {
        return [
            'success',
            'apiCode',
            'message',
            'data',
            'debugData',
        ];
    }
}
