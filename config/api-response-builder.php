<?php

return [
    /**
     * Configurations for ApiCode.
     */
    'api_code' => [
        /**
         * The enumeration class name used for ApiCode.
         *
         * Notice that there are few points to customize your ApiCode enumeration, please
         * see documents for more details.
         */
        'class' => Juanyaolin\ApiResponseBuilder\ApiCodes\DefaultApiCodeEnum::class,
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
        'structure' => Juanyaolin\ApiResponseBuilder\Structures\DefaultStructure::class,

        /**
         * JSON encoding options.
         */
        'encoding_options' => JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE,
    ],

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
        'class' => Juanyaolin\ApiResponseBuilder\Responses\DefaultApiResponse::class,
    ],

    /**
     * Configurations for ExceptionRenderer.
     */
    'renderer' => [
        /**
         * Facade key name for facade ExceptionRenderer.
         */
        'facade' => 'ExceptionRenderer',

        /**
         * The class that ExceptionRenderer facade will be used.
         *
         * Feel free to change, and make sure to be a subclass of 'ExceptionRendererContract'.
         */
        'class' => Juanyaolin\ApiResponseBuilder\Renderers\DefaultRenderer::class,

        /**
         * Exception adaptors for ExceptionRenderer.
         *
         * Renderer will check if the exception is subclass of key of the array and decide which adapter to use.
         * Each element must contain the following two key-value pair.
         *
         * Key-value pair:
         *  - 'adaptor'  : The adaptor class. The adaptor must be a subclass of 'ExceptionAdaptorContract'.
         *  - 'priority' : The priority of adaptor. The adaptor with the higher priority will be used if matched.
         *
         * @see Juanyaolin\ApiResponseBuilder\Contracts\ExceptionAdaptorContract
         *
         * @example
         *  'adaptors' => [
         *      \Juanyaolin\ApiResponseBuilder\Exceptions\ApiException::class => [
         *          'adaptor' => \Juanyaolin\ApiResponseBuilder\Adaptors\ApiExceptionAdaptor::class,
         *          'priority' => -100,
         *      ],
         *     \Illuminate\Validation\ValidationException::class => [
         *         'adaptor' => \Juanyaolin\ApiResponseBuilder\Adaptors\ValidationExceptionAdaptor::class,
         *         'priority' => -100,
         *     ],
         *     \Symfony\Component\HttpKernel\Exception\HttpException::class => [
         *         'adaptor' => \Juanyaolin\ApiResponseBuilder\Adaptors\HttpExceptionAdaptor::class,
         *         'priority' => -100,
         *     ],
         *     \Illuminate\Auth\AuthenticationException::class => [
         *         'adaptor' => \Juanyaolin\ApiResponseBuilder\Adaptors\AuthenticationExceptionAdaptor::class,
         *         'priority' => -100,
         *     ],
         *     'default' => [
         *         'adaptor' => \Juanyaolin\ApiResponseBuilder\Adaptors\DefaultExceptionAdaptor::class,
         *         'priority' => -127,
         *     ],
         *  ]
         */
        'adaptors' => [
            Juanyaolin\ApiResponseBuilder\Exceptions\ApiException::class => [
                'adaptor' => Juanyaolin\ApiResponseBuilder\Adaptors\ApiExceptionAdaptor::class,
                'priority' => -100,
            ],
            Illuminate\Validation\ValidationException::class => [
                'adaptor' => Juanyaolin\ApiResponseBuilder\Adaptors\ValidationExceptionAdaptor::class,
                'priority' => -100,
            ],
            Symfony\Component\HttpKernel\Exception\HttpException::class => [
                'adaptor' => Juanyaolin\ApiResponseBuilder\Adaptors\HttpExceptionAdaptor::class,
                'priority' => -100,
            ],
            Illuminate\Auth\AuthenticationException::class => [
                'adaptor' => Juanyaolin\ApiResponseBuilder\Adaptors\AuthenticationExceptionAdaptor::class,
                'priority' => -100,
            ],
            'default' => [
                'adaptor' => Juanyaolin\ApiResponseBuilder\Adaptors\DefaultExceptionAdaptor::class,
                'priority' => -127,
            ],
        ],
    ],
];
