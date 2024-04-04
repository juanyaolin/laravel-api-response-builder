<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

abstract class ApiResponseStructureContract
{
    /** Determine if the api is processed successful. */
    protected bool $success;

    /** The api code of response. */
    protected int|string $apiCode;

    /** Http status code. */
    protected int $statusCode;

    /** The message of response. */
    protected string $message;

    /** The data of response. */
    protected mixed $data;

    /** The data for debugging. */
    protected ?array $debugData = null;

    /** Additional data for developer customization. */
    protected ?array $additional = null;

    public function __construct(
        bool $success,
        int|string $apiCode,
        int $statusCode,
        string $message,
        mixed $data = null,
        ?array $debugData = null,
        ?array $additional = null
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
