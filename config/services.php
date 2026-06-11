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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'whatsapp' => [
        'graph_version' => env('WHATSAPP_GRAPH_VERSION', 'v19.0'),
        'token' => env('WHATSAPP_ACCESS_TOKEN'),
        'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
        'verify_token' => env('WHATSAPP_VERIFY_TOKEN'),
        'app_secret' => env('WHATSAPP_APP_SECRET'),
        'ai' => [
            'allowed_numbers' => array_values(array_filter(array_map('trim', explode(',', env('WHATSAPP_AI_ALLOWED_NUMBERS', ''))))),
            'privileged_numbers' => array_values(array_filter(array_map('trim', explode(',', env('WHATSAPP_AI_PRIVILEGED_NUMBERS', ''))))),
            'fernanda_number' => env('WHATSAPP_AI_FERNANDA_NUMBER'),
            'welcome_template' => env('WHATSAPP_AI_WELCOME_TEMPLATE', 'bienvenida_fernanda_ai'),
            'welcome_template_language' => env('WHATSAPP_AI_WELCOME_TEMPLATE_LANGUAGE', 'es_MX'),
            'max_reply_chars' => (int) env('WHATSAPP_AI_MAX_REPLY_CHARS', 3600),
            'send_oficio_document' => filter_var(env('WHATSAPP_AI_SEND_OFICIO_DOCUMENT', true), FILTER_VALIDATE_BOOLEAN),
            'signature_name' => env('WHATSAPP_AI_SIGNATURE_NAME'),
            'signature_position' => env('WHATSAPP_AI_SIGNATURE_POSITION'),
        ],
    ],

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-5-mini'),
        'timeout' => (int) env('OPENAI_TIMEOUT', 45),
        'max_output_tokens' => (int) env('OPENAI_MAX_OUTPUT_TOKENS', 2200),
    ],

];
