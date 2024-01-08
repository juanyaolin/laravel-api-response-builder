<?php

namespace Juanyaolin\ApiResponseBuilder;

use Juanyaolin\ApiResponseBuilder\ApiResponseBuilderConstant as Constant;
use Juanyaolin\ApiResponseBuilder\Contracts\ApiCodeContract;
use Juanyaolin\ApiResponseBuilder\Exceptions\InvalidTypeOfApiCodeException;
use MyCLabs\Enum\Enum;
use Symfony\Component\HttpFoundation\Response;
use UnitEnum;

class ApiResponseBuilder
{
    /**
     * Determine if the api is processed successful.
     */
    protected bool $success;

    /**
     * The api code of response.
     *
     * @var int|string
     */
    protected $apiCode;

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
    protected $data;

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
     * The payload of response to build.
     */
    protected array $responsePayload;

    /**
     * Only can be initiated by asSuccess() and asError().
     *
     * @param int|string|null $apiCode
     */
    protected function __construct(bool $success, $apiCode = null)
    {
        throw_if(
            !is_null($apiCode) && !is_int($apiCode) && !is_string($apiCode),
            InvalidTypeOfApiCodeException::class,
            gettype($apiCode)
        );

        $this->success = $success;
        $this->apiCode = $apiCode ?? $this->defaultApiCode();
        $this->statusCode = $this->apiCodeEnum()->statusCode();
        $this->message = $this->apiCodeEnum()->message();
        $this->data = null;
        $this->debugData = null;
        $this->additional = null;
        $this->httpHeaders = [];
        $this->jsonOptions = config(
            Constant::CONF_KEY_BUILDER_ENCODING_OPTIONS,
            Constant::DEFAULT_ENCODING_OPTIONS
        );
    }

    /**
     * Initiate an instance of ApiResponseBuilder as api called successful.
     *
     * By default, make a response in Success case of ApiCode.
     *
     * @param int|string|null $apiCode
     *
     * @return static
     */
    public static function asSuccess($apiCode = null)
    {
        return new static(true, $apiCode);
    }

    /**
     * Initiate an instance of ApiResponseBuilder as api called failed.
     *
     * By default, make a response in UncaughtException case of ApiCode.
     *
     * @param int|string|null $apiCode
     *
     * @return static
     */
    public static function asError($apiCode = null)
    {
        return new static(false, $apiCode);
    }

    /**
     * Specify http status code of response.
     *
     * @return $this
     */
    public function withStatusCode(int $statusCode = null)
    {
        if (!is_null($statusCode)) {
            $this->statusCode = $statusCode;
        }

        return $this;
    }

    /**
     * Specify message of response.
     *
     * @return $this
     */
    public function withMessage(string $message = null)
    {
        if (!is_null($message)) {
            $this->message = $message;
        }

        return $this;
    }

    /**
     * Specify data of response.
     *
     * @return $this
     */
    public function withData($data = null)
    {
        if (!is_null($data)) {
            $this->data = $data;
        }

        return $this;
    }

    /**
     * Sepcify debugData of response.
     *
     * @return $this
     */
    public function withDebugData(array $debugData = null)
    {
        if (!is_null($debugData)) {
            $this->debugData = $debugData;
        }

        return $this;
    }

    /**
     * Bring in the additional information for customized response generating.
     *
     * @return $this
     */
    public function withAdditional(array $additional = null)
    {
        if (!is_null($additional)) {
            $this->additional = $additional;
        }

        return $this;
    }

    /**
     * Customize http response header.
     *
     * @return $this
     */
    public function withHttpHeaders(array $httpHeaders = null)
    {
        if (!is_null($httpHeaders)) {
            $this->httpHeaders = $httpHeaders;
        }

        return $this;
    }

    /**
     * Sepcify json encoding options.
     *
     * @return $this
     */
    public function withJsonOptions(int $jsonOptions = null)
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
    protected function defaultapiCode()
    {
        $apiCodeClass = config(Constant::CONF_KEY_API_CODE_CLASS);

        if (is_subclass_of($apiCodeClass, Enum::class)) {
            /** @var ApiCodeContract */
            $apiCodeEnum = $this->success
                ? $apiCodeClass::Success()
                : $apiCodeClass::Error();
        } else {
            /** @var ApiCodeContract */
            $apiCodeEnum = $this->success
                ? $apiCodeClass::Success
                : $apiCodeClass::Error;
        }

        return $apiCodeEnum->apiCode();
    }

    /**
     * The enum of provided api code.
     *
     * @return ApiCodeContract|Enum|UnitEnum
     */
    protected function apiCodeEnum()
    {
        $apiCodeClass = config(Constant::CONF_KEY_API_CODE_CLASS);

        return is_subclass_of($apiCodeClass, Enum::class)
            ? new $apiCodeClass($this->apiCode)
            : $apiCodeClass::tryFrom($this->apiCode);
    }

    /**
     * Setup the structure of response payload.
     *
     * @return $this
     */
    protected function setupResponseStructure()
    {
        $structureClass = config(Constant::CONF_KEY_BUILDER_STRUCTURE);

        $this->responsePayload = (new $structureClass(
            $this->success,
            $this->apiCode,
            $this->statusCode,
            $this->message,
            $this->data,
            $this->debugData,
            $this->additional,
        ))->make();

        return $this;
    }

    /**
     * Make json response by laravel helper function.
     */
    protected function makeResponse(): Response
    {
        return response()->json(
            $this->responsePayload,
            $this->statusCode,
            $this->httpHeaders,
            $this->jsonOptions
        );
    }
}
