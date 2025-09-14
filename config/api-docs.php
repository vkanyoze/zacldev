<?php

return [
    'openapi' => '3.0.0',
    'info' => [
        'title' => 'Payment API',
        'description' => 'API for processing and managing payments',
        'version' => '1.0.0',
        'contact' => [
            'email' => 'support@example.com'
        ],
    ],
    'servers' => [
        [
            'url' => env('APP_URL', 'http://localhost:8000') . '/api',
            'description' => 'API Server',
        ],
    ],
    'paths' => [],
    'components' => [
        'securitySchemes' => [
            'bearerAuth' => [
                'type' => 'http',
                'scheme' => 'bearer',
                'bearerFormat' => 'JWT',
            ],
        ],
        'schemas' => [
            'Payment' => [
                'type' => 'object',
                'properties' => [
                    'id' => [
                        'type' => 'integer',
                        'format' => 'int64',
                        'example' => 1,
                    ],
                    'amount' => [
                        'type' => 'number',
                        'format' => 'float',
                        'example' => 99.99,
                    ],
                    'currency' => [
                        'type' => 'string',
                        'example' => 'USD',
                    ],
                    'description' => [
                        'type' => 'string',
                        'example' => 'Monthly subscription',
                    ],
                    'status' => [
                        'type' => 'string',
                        'enum' => ['pending', 'completed', 'failed', 'refunded'],
                        'example' => 'completed',
                    ],
                    'created_at' => [
                        'type' => 'string',
                        'format' => 'date-time',
                    ],
                    'updated_at' => [
                        'type' => 'string',
                        'format' => 'date-time',
                    ],
                ],
            ],
            'PaymentRequest' => [
                'type' => 'object',
                'required' => ['amount', 'currency', 'description', 'card_id'],
                'properties' => [
                    'amount' => [
                        'type' => 'number',
                        'format' => 'float',
                        'minimum' => 0.01,
                        'example' => 99.99,
                    ],
                    'currency' => [
                        'type' => 'string',
                        'minLength' => 3,
                        'maxLength' => 3,
                        'example' => 'USD',
                    ],
                    'description' => [
                        'type' => 'string',
                        'maxLength' => 255,
                        'example' => 'Monthly subscription',
                    ],
                    'card_id' => [
                        'type' => 'integer',
                        'format' => 'int64',
                        'example' => 1,
                    ],
                ],
            ],
            'Error' => [
                'type' => 'object',
                'properties' => [
                    'message' => [
                        'type' => 'string',
                        'example' => 'Error message',
                    ],
                    'errors' => [
                        'type' => 'object',
                        'example' => [
                            'field' => ['The field is required.']
                        ],
                    ],
                ],
            ],
        ],
        'responses' => [
            'Unauthenticated' => [
                'description' => 'Unauthenticated',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            '$ref' => '#/components/schemas/Error',
                        ],
                        'example' => [
                            'message' => 'Unauthenticated.',
                        ],
                    ],
                ],
            ],
            'ValidationError' => [
                'description' => 'Validation Error',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            '$ref' => '#/components/schemas/Error',
                        ],
                        'example' => [
                            'message' => 'The given data was invalid.',
                            'errors' => [
                                'field' => ['The field is required.'],
                            ],
                        ],
                    ],
                ],
            ],
            'NotFound' => [
                'description' => 'Resource not found',
                'content' => [
                    'application/json' => [
                        'schema' => [
                            '$ref' => '#/components/schemas/Error',
                        ],
                        'example' => [
                            'message' => 'Resource not found',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'security' => [
        ['bearerAuth' => []],
    ],
];
