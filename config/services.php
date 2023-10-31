<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'camunda' => [
        'url' => env('CAMUNDA_URL', 'https://localhost:8080/engine-rest'),
        'user' => env('CAMUNDA_USER', 'demo'),
        'password' => env('CAMUNDA_PASSWORD', 'demo'),
        'tenant_id' => env('CAMUNDA_TENANT_ID', ''),
    ],

    'odoo' => [
        'url' => env('ODOO_API_URL', 'localhost:8000'),
        'user' => env('ODOO_USERNAME', 'demo'),
        'password' => env('ODOO_PASSWORD', 'demo'),
        'database' => env('ODOO_DATABASE', 'demo'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'client' => [
        'url' => env('APP_URL_CLIENT')
    ]

];
