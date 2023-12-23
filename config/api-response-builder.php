<?php

return [

    /*
     * Configurations for ApiResponse class.
     */
    'response' => [

        /*
         * Facade key name.
         */
        'facade' => 'ApiResponse',

        /*
         * Singleton class of facade. Change if needed, and must be a instance
         * of \Juanyaolin\ApiResponseBuilder\Contracts\ApiResponseInterface
         */
        'class' => Juanyaolin\ApiResponseBuilder\ApiResponse::class,
    ],

    /*
     * Configurations for ApiResponseBuilder class.
     */
    'builder' => [

        /*
         * Response structure
         *
         * Keys of structure array can be customize if needed. Value of structure
         * can only from avaiable variable followed. If unavailable variable used,
         * response will not include it.
         *
         * Available variables:
         *  - $success      : Determine if the response is success or not.
         *  - $apiCode      : The apiCode of response. By default, '0' as success,
         *                    and '-1' as uncaught exception.
         *  - $message      : The message of response.
         *  - $data         : The data of response.
         *  - $debugData:   : The data for debugging failed api call. This attribute
         *                    will be present while $success is false, 'app.debug'
         *                    as true and value of attribute is not null, by default.
         *
         * @see \Juanyaolin\ApiResponseBuilder\ApiResponseBuilder
         *
         * @example
         *  'structure' => [
         *      'success' => '$success',
         *      'apiCode' => '$apiCode',
         *      'message' => '$message',
         *      'data' => '$data',
         *      'debugData' => '$debugData',
         *  ],
         */
        'structure' => [
            'success' => '$success',
            'apiCode' => '$apiCode',
            'message' => '$message',
            'data' => '$data',
            'debugData' => '$debugData',
        ],

        /*
         * JSON encoding options.
         */
        'encoding_options' => JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE,
    ],
];
