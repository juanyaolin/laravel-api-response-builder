<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

abstract class ApiResponseStructureContract
{
    /**
     * Determine if the api is processed successful.
     */
    protected ?bool $success = null;

    /**
     * The api code of response.
     *
     * @var int|string|null
     */
    protected $apiCode;

    /**
     * Http status code.
     */
    protected ?int $statusCode = null;

    /**
     * The message of response.
     */
    protected ?string $message = null;

    /**
     * The data of response.
     */
    protected $data;

    /**
     * The data for debugging.
     */
    protected ?array $debugData = null;

    /**
     * Additional data for developer customization.
     */
    protected ?array $additional = null;

    public function __construct(
        bool $success = null,
        $apiCode = null,
        int $statusCode = null,
        string $message = null,
        $data = null,
        array $debugData = null,
        array $additional = null
    ) {
        $this->success = $success;
        $this->apiCode = $apiCode;
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->data = $data;
        $this->debugData = $debugData;
        $this->additional = $additional;
    }

    /**
     * The sturcture will be present in response.
     */
    abstract public function make(): array;
}
