<?php

namespace Juanyaolin\ApiResponseBuilder\Contracts;

/**
 * @property bool|null   $success    Determine if the api is processed successful
 * @property int|null    $apiCode    The api code of response
 * @property int|null    $statusCode Http status code
 * @property string|null $message    The message of response
 * @property mixed|null  $data       The data of response
 * @property array|null  $debugData  The data for debugging
 * @property array|null  $additional Additional data for developer customization
 */
abstract class ApiResponseStructureContract
{
    public function __construct(
        protected ?bool $success = null,
        protected ?int $apiCode = null,
        protected ?int $statusCode = null,
        protected ?string $message = null,
        protected mixed $data = null,
        protected ?array $debugData = null,
        protected ?array $additional = null
    ) {
    }

    /**
     * The sturcture will be present in response.
     */
    abstract public function make(): array;
}
