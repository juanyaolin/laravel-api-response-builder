<?php

return [
    /**
     * Configurations for ApiResponse.
     */
    'response' => [
        /**
         * Facade key name for facade ApiResponse.
         */
        'facade' => 'ApiResponse',

        /**
         * The class that ApiResponse facade will be used.
         *
         * Feel free to change, and make sure to be a subclass of 'ApiResponseContract'.
         */
        'class' => \Juanyaolin\ApiResponseBuilder\DefaultApiResponse::class,
    ],

    /**
     * Configurations for ApiResponseBuilder.
     */
    'builder' => [
        /**
         * The structure of response will be built in ApiResponseBuilder.
         *
         * Feel free to change, and make sure to be a subclass of 'ApiResponseStructureContract'.
         */
        'structure' => \Juanyaolin\ApiResponseBuilder\DefaultApiResponseStructure::class,

        /**
         * JSON encoding options.
         */
        'encoding_options' => JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE,
    ],

    /**
     * Configurations for ExceptionRenderer.
     */
    'exception_renderer' => [
        /**
         * Facade key name for facade ExceptionRenderer.
         */
        'facade' => 'ExceptionRenderer',

        /**
         * The class that ExceptionRenderer facade will be used.
         *
         * Feel free to change, and make sure to be a subclass of 'ExceptionRendererContract'.
         */
        'class' => \Juanyaolin\ApiResponseBuilder\DefaultExceptionRenderer::class,

        /**
         * The enumeration class name used by ApiCodes.
         *
         * PHP does not allow inheritance on enumeration. Therefore, if you want to customize your own api codes, please
         * create an ApiCode enumeration yourself and implement interface 'ApiCodeContract'.
         */
        'api_codes' => \Juanyaolin\ApiResponseBuilder\DefaultApiCode::class,

        /**
         * Exception adaptors for ExceptionRenderer.
         *
         * Renderer will check if the exception is subclass of key of the array and decide which adapter to use.
         * Each element must contain the following two key-value pair.
         *
         * Key-value pair:
         *  - 'adaptor'  : The adaptor class.The adaptor must be a subclass of 'ExceptionAdaptorContract'.
         *  - 'priority' : The priority of adaptor. The adaptor with the higher priority will be used.
         *
         * @see Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract
         *
         * @example
         *  'adaptors' => [
         *      \Juanyaolin\ApiResponseBuilder\Exceptions\ApiException::class => [
         *          'adaptor' => \Juanyaolin\ApiResponseBuilder\ExceptionAdaptors\ApiExceptionAdaptor::class,
         *          'priority' => -100,
         *      ],
         *     \Illuminate\Validation\ValidationException::class => [
         *         'adaptor' => \Juanyaolin\ApiResponseBuilder\ExceptionAdaptors\ValidationExceptionAdaptor::class,
         *         'priority' => -100,
         *     ],
         *     \Symfony\Component\HttpKernel\Exception\HttpException::class => [
         *         'adaptor' => \Juanyaolin\ApiResponseBuilder\ExceptionAdaptors\HttpExceptionAdaptor::class,
         *         'priority' => -100,
         *     ],
         *     \Illuminate\Auth\AuthenticationException::class => [
         *         'adaptor' => \Juanyaolin\ApiResponseBuilder\ExceptionAdaptors\AuthenticationExceptionAdaptor::class,
         *         'priority' => -100,
         *     ],
         *     'default' => [
         *         'adaptor' => \Juanyaolin\ApiResponseBuilder\ExceptionAdaptors\DefaultExceptionAdaptor::class,
         *         'priority' => -127,
         *     ],
         *  ]
         */
        'adaptors' => [
            \Juanyaolin\ApiResponseBuilder\Exceptions\ApiException::class => [
                'adaptor' => \Juanyaolin\ApiResponseBuilder\ExceptionAdaptors\ApiExceptionAdaptor::class,
                'priority' => -100,
            ],
            \Illuminate\Validation\ValidationException::class => [
                'adaptor' => \Juanyaolin\ApiResponseBuilder\ExceptionAdaptors\ValidationExceptionAdaptor::class,
                'priority' => -100,
            ],
            \Symfony\Component\HttpKernel\Exception\HttpException::class => [
                'adaptor' => \Juanyaolin\ApiResponseBuilder\ExceptionAdaptors\HttpExceptionAdaptor::class,
                'priority' => -100,
            ],
            \Illuminate\Auth\AuthenticationException::class => [
                'adaptor' => \Juanyaolin\ApiResponseBuilder\ExceptionAdaptors\AuthenticationExceptionAdaptor::class,
                'priority' => -100,
            ],
            'default' => [
                'adaptor' => \Juanyaolin\ApiResponseBuilder\ExceptionAdaptors\DefaultExceptionAdaptor::class,
                'priority' => -127,
            ],
        ],
    ],
];
