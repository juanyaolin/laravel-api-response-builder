<?php

namespace Juanyaolin\ApiResponseBuilder;

interface ApiResponseBuilderConstant
{
    /*
     * Configuration keys.
     */
    public const CONF_CONFIG = 'api-response-builder';
    public const CONF_KEY_API_CODE = self::CONF_CONFIG . '.api_code';
    public const CONF_KEY_API_CODE_CLASS = self::CONF_KEY_API_CODE . '.class';
    public const CONF_KEY_RESPONSE = self::CONF_CONFIG . '.response';
    public const CONF_KEY_RESPONSE_FACADE = self::CONF_KEY_RESPONSE . '.facade';
    public const CONF_KEY_RESPONSE_CLASS = self::CONF_KEY_RESPONSE . '.class';
    public const CONF_KEY_BUILDER = self::CONF_CONFIG . '.builder';
    public const CONF_KEY_BUILDER_STRUCTURE = self::CONF_KEY_BUILDER . '.structure';
    public const CONF_KEY_BUILDER_ENCODING_OPTIONS = self::CONF_KEY_BUILDER . '.encoding_options';
    public const CONF_KEY_RENDERER = self::CONF_CONFIG . '.renderer';
    public const CONF_KEY_RENDERER_FACADE = self::CONF_KEY_RENDERER . '.facade';
    public const CONF_KEY_RENDERER_CLASS = self::CONF_KEY_RENDERER . '.class';
    public const CONF_KEY_RENDERER_ADAPTORS = self::CONF_KEY_RENDERER . '.adaptors';

    /*
     * Regular keys.
     */
    public const KEY_DEFAULT = 'default';
    public const KEY_ADAPTOR = 'adaptor';
    public const KEY_PRIORITY = 'priority';

    /**
     * Essential api codes.
     */
    public const ESSENTIAL_API_CODES = [
        'Success',
        'UncaughtException',
        'ValidationException',
        'AuthenticationException',
        'HttpException',
        'ApiException',
    ];

    /**
     * Default JSON encoding options.
     */
    public const DEFAULT_ENCODING_OPTIONS = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;

    /**
     * Default adpators of exception renderer.
     */
    public const DEFAULT_RENDERER_ADAPTORS = [
        Exceptions\ApiException::class => [
            self::KEY_ADAPTOR => Adaptors\ApiExceptionAdaptor::class,
            self::KEY_PRIORITY => -100,
        ],
        \Illuminate\Validation\ValidationException::class => [
            self::KEY_ADAPTOR => Adaptors\ValidationExceptionAdaptor::class,
            self::KEY_PRIORITY => -100,
        ],
        \Symfony\Component\HttpKernel\Exception\HttpException::class => [
            self::KEY_ADAPTOR => Adaptors\HttpExceptionAdaptor::class,
            self::KEY_PRIORITY => -100,
        ],
        \Illuminate\Auth\AuthenticationException::class => [
            self::KEY_ADAPTOR => Adaptors\AuthenticationExceptionAdaptor::class,
            self::KEY_PRIORITY => -100,
        ],
        self::KEY_DEFAULT => [
            self::KEY_ADAPTOR => Adaptors\DefaultExceptionAdaptor::class,
            self::KEY_PRIORITY => -127,
        ],
    ];
}
