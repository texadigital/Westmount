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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
    ],

    'bank' => [
        'name' => env('BANK_NAME', 'Banque Nationale du Canada'),
        'account_holder' => env('BANK_ACCOUNT_HOLDER', 'Association Westmount'),
        'account_number' => env('BANK_ACCOUNT_NUMBER'),
        'transit_number' => env('BANK_TRANSIT_NUMBER'),
        'institution_number' => env('BANK_INSTITUTION_NUMBER'),
        'swift_code' => env('BANK_SWIFT_CODE'),
        'routing_number' => env('BANK_ROUTING_NUMBER'),
    ],

    'interac' => [
        'email' => env('INTERAC_EMAIL', 'paiements@associationwestmount.com'),
        'name' => env('INTERAC_NAME', 'Association Westmount'),
        'security_question' => env('INTERAC_SECURITY_QUESTION', 'Quel est le nom de l\'association?'),
        'security_answer' => env('INTERAC_SECURITY_ANSWER', 'Westmount'),
    ],

];
