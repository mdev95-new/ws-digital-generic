<?php

return [
    'default' => 'email',
    'methods' => [
        'email' => [
            'from' => env('DELIVERY_MAIL_FROM', 'shop@ws-digital-generic.com'),
        ],
        'telegram' => [
            'bot_token' => env('DELIVERY_TELEGRAM_BOT_TOKEN'),
            'api_url' => 'https://api.telegram.org/bot',
        ],
        'whatsapp' => [
            'api_key' => env('DELIVERY_WHATSAPP_API_KEY'),
            'api_url' => 'https://api.whatsapp.com/v1',
        ],
    ],
];